import argparse
import json
import os
import re
import zipfile
import xml.etree.ElementTree as ET


def parse_args():
    parser = argparse.ArgumentParser(description="Document screening using YOLOv8 and OCR")
    parser.add_argument("--input", required=True, dest="input_path")
    parser.add_argument("--output", required=True, dest="output_path")
    parser.add_argument("--ocr-lang", default="eng+ind", dest="ocr_lang")
    parser.add_argument("--model", default="yolov8n.pt", dest="model")
    parser.add_argument("--required-classes", default="heading,title,paragraph", dest="required_classes")
    parser.add_argument("--allowed-fonts", default="Times New Roman,Calibri,Arial,Cambria", dest="allowed_fonts")
    parser.add_argument("--min-paragraphs", type=int, default=5, dest="min_paragraphs")
    parser.add_argument("--min-section-hits", type=int, default=3, dest="min_section_hits")
    parser.add_argument("--margin-min-ratio", type=float, default=0.02, dest="margin_min_ratio")
    parser.add_argument("--max-pages", type=int, default=3, dest="max_pages")
    parser.add_argument("--pass-score", type=int, default=70, dest="pass_score")
    parser.add_argument("--tesseract-cmd", default="", dest="tesseract_cmd")
    return parser.parse_args()


def bool_check(passed, message, source):
    return {"passed": bool(passed), "message": str(message), "source": source}


def extract_docx_xml(path):
    with zipfile.ZipFile(path, "r") as zf:
        document_xml = zf.read("word/document.xml").decode("utf-8", errors="ignore") if "word/document.xml" in zf.namelist() else ""
        settings_xml = zf.read("word/settings.xml").decode("utf-8", errors="ignore") if "word/settings.xml" in zf.namelist() else ""
        styles_xml = zf.read("word/styles.xml").decode("utf-8", errors="ignore") if "word/styles.xml" in zf.namelist() else ""
        media_files = [name for name in zf.namelist() if name.startswith("word/media/")]
        media_blobs = [(name, zf.read(name)) for name in media_files]

    return document_xml, settings_xml, styles_xml, media_blobs


def plain_text_from_docx_xml(document_xml):
    if not document_xml:
        return ""
    try:
        root = ET.fromstring(document_xml)
    except ET.ParseError:
        return ""

    texts = []
    for node in root.iter():
        if node.tag.endswith("}t") and node.text:
            texts.append(node.text)
    return " ".join(texts)


def checks_from_docx_xml(document_xml, settings_xml, styles_xml, min_paragraphs=5, allowed_fonts_csv="Times New Roman,Calibri,Arial,Cambria"):
    heading_count = len(re.findall(r'w:pStyle[^>]*w:val="Heading[1-3]"', document_xml, flags=re.IGNORECASE))
    heading_passed = heading_count > 0

    paper_passed = False
    pg = re.search(r'w:pgSz[^>]*w:w="(\d+)"[^>]*w:h="(\d+)"', settings_xml, flags=re.IGNORECASE)
    if pg:
        w = int(pg.group(1))
        h = int(pg.group(2))
        for aw, ah in [(11906, 16838), (16838, 11906)]:
            if abs(w - aw) <= 300 and abs(h - ah) <= 300:
                paper_passed = True
                break

    margin_passed = False
    mg = re.search(
        r'w:pgMar[^>]*w:top="(\d+)"[^>]*w:right="(\d+)"[^>]*w:bottom="(\d+)"[^>]*w:left="(\d+)"',
        settings_xml,
        flags=re.IGNORECASE,
    )
    if mg:
        top, right, bottom, left = [int(mg.group(i)) for i in range(1, 5)]
        target = 1440
        tolerance = 240
        margin_passed = (
            abs(top - target) <= tolerance
            and abs(right - target) <= tolerance
            and abs(bottom - target) <= tolerance
            and abs(left - target) <= tolerance
        )

    footer_ref_count = len(re.findall(r"w:footerReference", document_xml, flags=re.IGNORECASE))
    footer_distance_cm = None
    footer_match = re.search(r'w:pgMar[^>]*w:footer="(\d+)"', settings_xml + " " + document_xml, flags=re.IGNORECASE)
    if footer_match:
        footer_distance_cm = round(int(footer_match.group(1)) / 567, 2)
    footer_passed = footer_ref_count > 0

    spacing_xml = document_xml + " " + styles_xml
    line_spacing = None
    line_rule = "auto"
    line_match = re.search(r'w:spacing[^>]*w:line="(\d+)"', spacing_xml, flags=re.IGNORECASE)
    if line_match:
        line_spacing = round(int(line_match.group(1)) / 240, 2)
    line_rule_match = re.search(r'w:spacing[^>]*w:lineRule="([^"]+)"', spacing_xml, flags=re.IGNORECASE)
    if line_rule_match:
        line_rule = str(line_rule_match.group(1))

    before_match = re.search(r'w:spacing[^>]*w:before="(\d+)"', spacing_xml, flags=re.IGNORECASE)
    after_match = re.search(r'w:spacing[^>]*w:after="(\d+)"', spacing_xml, flags=re.IGNORECASE)
    ind_left_match = re.search(r'w:ind[^>]*w:left="(\d+)"', spacing_xml, flags=re.IGNORECASE)
    ind_right_match = re.search(r'w:ind[^>]*w:right="(\d+)"', spacing_xml, flags=re.IGNORECASE)
    ind_first_match = re.search(r'w:ind[^>]*w:firstLine="(\d+)"', spacing_xml, flags=re.IGNORECASE)

    spacing_before_pt = round((int(before_match.group(1)) if before_match else 0) / 20, 1)
    spacing_after_pt = round((int(after_match.group(1)) if after_match else 0) / 20, 1)
    indent_left_cm = round((int(ind_left_match.group(1)) if ind_left_match else 0) / 567, 2)
    indent_right_cm = round((int(ind_right_match.group(1)) if ind_right_match else 0) / 567, 2)
    indent_first_cm = round((int(ind_first_match.group(1)) if ind_first_match else 0) / 567, 2)

    spacing_padding_passed = True
    if line_spacing is not None and (line_spacing < 1.0 or line_spacing > 2.5):
        spacing_padding_passed = False
    if spacing_before_pt > 24 or spacing_after_pt > 24:
        spacing_padding_passed = False
    if indent_left_cm > 2.5 or indent_right_cm > 2.5 or indent_first_cm > 2.5:
        spacing_padding_passed = False

    paragraph_count = len(re.findall(r"<w:p[ >]", document_xml, flags=re.IGNORECASE))
    structure_passed = heading_passed and paragraph_count >= max(1, int(min_paragraphs))

    font_names = re.findall(r'w:rFonts[^>]*w:ascii="([^"]+)"', document_xml + " " + styles_xml, flags=re.IGNORECASE)
    unique_fonts = []
    for f in font_names:
        ff = f.strip()
        if ff and ff not in unique_fonts:
            unique_fonts.append(ff)

    allowed_fonts = {x.strip() for x in str(allowed_fonts_csv).split(",") if x.strip()}
    if not allowed_fonts:
        allowed_fonts = {"Times New Roman", "Calibri", "Arial", "Cambria"}
    font_passed = len(unique_fonts) > 0
    if font_passed:
        non_standard = [f for f in unique_fonts if f not in allowed_fonts]
        font_passed = len(non_standard) <= 2

    checks = {
        "heading": bool_check(heading_passed, f"Heading terdeteksi: {heading_count}", "xml"),
        "footer": bool_check(
            footer_passed,
            f"Footer terdeteksi {footer_ref_count} section" + (f" (jarak footer: {footer_distance_cm} cm)" if footer_distance_cm is not None else ""),
            "xml",
        ),
        "paper_size": bool_check(paper_passed, "Ukuran kertas A4" if paper_passed else "Ukuran kertas belum A4", "xml"),
        "margin": bool_check(margin_passed, "Margin sekitar 2.54cm" if margin_passed else "Margin belum sesuai standar", "xml"),
        "spacing_padding": bool_check(
            spacing_padding_passed,
            f"Spacing/Padding: line spacing {line_spacing if line_spacing is not None else '-'} ({line_rule}), before {spacing_before_pt} pt, after {spacing_after_pt} pt, indent kiri {indent_left_cm} cm, kanan {indent_right_cm} cm, first-line {indent_first_cm} cm",
            "xml",
        ),
        "structure": bool_check(
            structure_passed,
            "Struktur dokumen baik" if structure_passed else "Struktur dokumen belum konsisten",
            "xml",
        ),
        "font": bool_check(
            font_passed,
            "Font terdeteksi: " + ", ".join(unique_fonts[:6]) if unique_fonts else "Font tidak terbaca",
            "xml",
        ),
    }

    return checks, unique_fonts


def try_load_optional_modules():
    modules = {
        "cv2": None,
        "np": None,
        "pytesseract": None,
        "fitz": None,
        "YOLO": None,
    }

    try:
        import cv2  # type: ignore
        modules["cv2"] = cv2
    except Exception:
        pass

    try:
        import numpy as np  # type: ignore
        modules["np"] = np
    except Exception:
        pass

    try:
        import pytesseract  # type: ignore
        modules["pytesseract"] = pytesseract
    except Exception:
        pass

    try:
        import fitz  # type: ignore
        modules["fitz"] = fitz
    except Exception:
        pass

    try:
        from ultralytics import YOLO  # type: ignore
        modules["YOLO"] = YOLO
    except Exception:
        pass

    return modules


def configure_tesseract_command(modules, tesseract_cmd):
    pytesseract_module = modules.get("pytesseract") if isinstance(modules, dict) else None
    if not pytesseract_module:
        return ""

    def find_tesseract_candidate():
        candidates = [
            os.environ.get("TESSERACT_CMD", "").strip(),
            os.environ.get("DOCUMENT_SCREENING_TESSERACT_CMD", "").strip(),
            (tesseract_cmd or "").strip(),
            r"C:\Program Files\Tesseract-OCR\tesseract.exe",
            r"C:\Program Files (x86)\Tesseract-OCR\tesseract.exe",
            r"D:\Tesseract-OCR\tesseract.exe",
        ]

        for cmd in candidates:
            if cmd and os.path.isfile(cmd):
                return cmd
        return ""

    cmd = find_tesseract_candidate()
    if not cmd:
        return ""

    try:
        pytesseract_module.pytesseract.tesseract_cmd = cmd
        return cmd
    except Exception:
        return ""


def images_from_pdf(path, fitz_module, cv2_module, np_module, max_pages=3):
    images = []
    if not fitz_module or not cv2_module or not np_module:
        return images

    try:
        doc = fitz_module.open(path)
        page_count = min(len(doc), max_pages)
        for idx in range(page_count):
            page = doc[idx]
            pix = page.get_pixmap(matrix=fitz_module.Matrix(2, 2), alpha=False)
            arr = np_module.frombuffer(pix.samples, dtype=np_module.uint8).reshape(pix.height, pix.width, pix.n)
            if pix.n == 4:
                arr = cv2_module.cvtColor(arr, cv2_module.COLOR_BGRA2BGR)
            elif pix.n == 3:
                arr = cv2_module.cvtColor(arr, cv2_module.COLOR_RGB2BGR)
            images.append(arr)
        doc.close()
    except Exception:
        return []

    return images


def normalize_font_name(font_name):
    normalized = re.sub(r"[^a-z0-9]+", " ", str(font_name or "").lower()).strip()
    return re.sub(r"\s+", " ", normalized)


def font_matches_allowed(font_name, allowed_fonts):
    normalized = normalize_font_name(font_name)
    if not normalized:
        return False

    for allowed in allowed_fonts:
        allowed_norm = normalize_font_name(allowed)
        if not allowed_norm:
            continue
        if allowed_norm in normalized or normalized in allowed_norm:
            return True
    return False


def collect_pdf_page_analysis(path, fitz_module, max_pages=3, allowed_fonts_csv="Times New Roman,Calibri,Arial,Cambria", min_section_hits=3, margin_min_ratio=0.02):
    if not fitz_module:
        return {
            "page_size_passed": False,
            "margin_passed": False,
            "font_passed": False,
            "heading_passed": False,
            "structure_passed": False,
            "text_sample": "",
            "ocr_fallback_needed": True,
            "pages_count": 0,
            "fonts": [],
            "page_size_message": "PDF engine tidak tersedia.",
            "margin_message": "PDF engine tidak tersedia.",
            "font_message": "Font PDF tidak tersedia.",
            "heading_message": "Heading PDF tidak tersedia.",
            "structure_message": "Struktur PDF tidak tersedia.",
            "ocr_heading": bool_check(False, "Heading PDF tidak tersedia.", "pdf"),
            "ocr_structure": bool_check(False, "Struktur PDF tidak tersedia.", "pdf"),
        }

    allowed_fonts = {x.strip() for x in str(allowed_fonts_csv).split(",") if x.strip()}
    if not allowed_fonts:
        allowed_fonts = {"Times New Roman", "Calibri", "Arial", "Cambria"}
    page_size_passed = True
    margin_passed = True
    fonts = []
    text_parts = []
    page_count = 0
    ocr_fallback_needed = False

    try:
        doc = fitz_module.open(path)
        page_count = min(len(doc), max_pages)
        for idx in range(page_count):
            page = doc[idx]
            rect = page.rect
            w = float(rect.width)
            h = float(rect.height)
            a4_candidates = [(595.0, 842.0), (842.0, 595.0)]
            if not any(abs(w - aw) <= 20 and abs(h - ah) <= 20 for aw, ah in a4_candidates):
                page_size_passed = False

            page_text = (page.get_text("text") or "").strip()
            if page_text:
                text_parts.append(page_text)
            else:
                ocr_fallback_needed = True

            page_dict = page.get_text("dict") or {}
            blocks = page_dict.get("blocks", []) or []
            block_boxes = []
            page_fonts = []

            for block in blocks:
                if block.get("type") != 0:
                    continue
                bbox = block.get("bbox")
                if bbox and len(bbox) == 4:
                    block_boxes.append(bbox)

                for line in block.get("lines", []) or []:
                    for span in line.get("spans", []) or []:
                        font_name = span.get("font")
                        if font_name:
                            page_fonts.append(str(font_name))

            for font_name in page_fonts:
                if font_name not in fonts:
                    fonts.append(font_name)

            if block_boxes:
                left = min(box[0] for box in block_boxes)
                top = min(box[1] for box in block_boxes)
                right = max(box[2] for box in block_boxes)
                bottom = max(box[3] for box in block_boxes)

                margin_left = left / max(1.0, w)
                margin_top = top / max(1.0, h)
                margin_right = (w - right) / max(1.0, w)
                margin_bottom = (h - bottom) / max(1.0, h)

                if not (
                    margin_left > margin_min_ratio
                    and margin_right > margin_min_ratio
                    and margin_top > margin_min_ratio
                    and margin_bottom > margin_min_ratio
                ):
                    margin_passed = False
            else:
                ocr_fallback_needed = True

        doc.close()
    except Exception:
        return {
            "page_size_passed": False,
            "margin_passed": False,
            "font_passed": False,
            "heading_passed": False,
            "structure_passed": False,
            "text_sample": "",
            "ocr_fallback_needed": True,
            "pages_count": 0,
            "fonts": [],
            "page_size_message": "Gagal membaca metadata PDF.",
            "margin_message": "Gagal membaca metadata PDF.",
            "font_message": "Gagal membaca metadata PDF.",
            "heading_message": "Gagal membaca metadata PDF.",
            "structure_message": "Gagal membaca metadata PDF.",
            "ocr_heading": bool_check(False, "Gagal membaca metadata PDF.", "pdf"),
            "ocr_structure": bool_check(False, "Gagal membaca metadata PDF.", "pdf"),
        }

    combined_text = "\n".join([p for p in text_parts if p]).strip()
    if not combined_text:
        ocr_fallback_needed = True

    heading_check, structure_check = heading_structure_from_ocr(combined_text, min_section_hits=min_section_hits)

    font_passed = False
    if fonts:
        font_passed = len([f for f in fonts if not font_matches_allowed(f, allowed_fonts)]) <= 2

    return {
        "page_size_passed": page_size_passed,
        "margin_passed": margin_passed,
        "font_passed": font_passed,
        "heading_passed": heading_check["passed"],
        "structure_passed": structure_check["passed"],
        "text_sample": combined_text,
        "ocr_fallback_needed": ocr_fallback_needed,
        "pages_count": page_count,
        "fonts": fonts,
        "page_size_message": "Ukuran halaman PDF terdeteksi A4." if page_size_passed else "Ukuran halaman PDF belum A4.",
        "margin_message": "Margin PDF terdeteksi normal." if margin_passed else "Margin PDF belum sesuai standar.",
        "font_message": "Font PDF terdeteksi: " + ", ".join(fonts[:6]) if fonts else "Font PDF belum terbaca.",
        "heading_message": heading_check["message"],
        "structure_message": structure_check["message"],
        "ocr_heading": heading_check,
        "ocr_structure": structure_check,
    }


def images_from_docx_media(media_blobs, cv2_module, np_module, max_images=5):
    images = []
    if not cv2_module or not np_module:
        return images

    for _, blob in media_blobs[:max_images]:
        try:
            arr = np_module.frombuffer(blob, dtype=np_module.uint8)
            img = cv2_module.imdecode(arr, cv2_module.IMREAD_COLOR)
            if img is not None:
                images.append(img)
        except Exception:
            continue
    return images


def ocr_text_from_images(images, pytesseract_module, ocr_lang, max_images=3):
    if not pytesseract_module or not images:
        return ""

    config = "--oem 3 --psm 6"
    parts = []
    for img in images[: max(1, int(max_images))]:
        try:
            text = pytesseract_module.image_to_string(img, lang=ocr_lang, config=config)
            if text:
                parts.append(text)
        except Exception:
            continue

    return "\n".join(parts)


def extract_percent_values(text):
    if not text:
        return []

    raw_values = re.findall(r"\b(\d{1,3}(?:[.,]\d+)?)\s*%\b", text)
    normalized = []
    for value in raw_values:
        value = value.replace(",", ".").strip()
        try:
            number = float(value)
        except ValueError:
            continue
        percent_text = f"{int(number) if number.is_integer() else number}%"
        if percent_text not in normalized:
            normalized.append(percent_text)
    return normalized


def detect_margin_from_ocr(images, pytesseract_module, ocr_lang, margin_min_ratio=0.02):
    if not pytesseract_module or not images:
        return None

    try:
        data = pytesseract_module.image_to_data(images[0], lang=ocr_lang, output_type=pytesseract_module.Output.DICT)
        xs = []
        ys = []
        ws = []
        hs = []

        for i, txt in enumerate(data.get("text", [])):
            if str(txt).strip() == "":
                continue
            try:
                xs.append(int(data["left"][i]))
                ys.append(int(data["top"][i]))
                ws.append(int(data["width"][i]))
                hs.append(int(data["height"][i]))
            except Exception:
                continue

        if not xs:
            return None

        img_h, img_w = images[0].shape[:2]
        left = min(xs) / max(1, img_w)
        top = min(ys) / max(1, img_h)
        right = (img_w - max(x + w for x, w in zip(xs, ws))) / max(1, img_w)
        bottom = (img_h - max(y + h for y, h in zip(ys, hs))) / max(1, img_h)

        passed = left > margin_min_ratio and right > margin_min_ratio and top > margin_min_ratio and bottom > margin_min_ratio
        return bool_check(passed, "Margin terdeteksi dari OCR" if passed else "Margin OCR terlalu sempit", "ocr")
    except Exception:
        return None


def heading_structure_from_ocr(ocr_text, min_section_hits=3):
    normalized = ocr_text or ""
    lines = [ln.strip() for ln in normalized.splitlines() if ln.strip()]

    heading_patterns = [
        r"^BAB\s+[IVXLC0-9]+",
        r"^CHAPTER\s+[IVXLC0-9]+",
        r"^[0-9]+(\.[0-9]+){0,2}\s+[A-Z]",
    ]

    heading_hits = 0
    for line in lines:
        if any(re.search(p, line, flags=re.IGNORECASE) for p in heading_patterns):
            heading_hits += 1

    heading_check = bool_check(heading_hits > 0, f"Heading OCR terdeteksi: {heading_hits}", "ocr")

    section_keywords = ["abstrak", "pendahuluan", "metode", "hasil", "pembahasan", "kesimpulan", "daftar pustaka"]
    keyword_hits = sum(1 for kw in section_keywords if re.search(r"\b" + re.escape(kw) + r"\b", normalized, flags=re.IGNORECASE))
    structure_check = bool_check(keyword_hits >= max(1, int(min_section_hits)), f"Struktur OCR terdeteksi ({keyword_hits} bagian)", "ocr")

    return heading_check, structure_check


def yolo_detect(images, yolo_cls, model_path, max_images=3):
    detected = []
    if not yolo_cls or not images:
        return detected

    try:
        model = yolo_cls(model_path)
    except Exception:
        return detected

    for img in images[: max(1, int(max_images))]:
        try:
            results = model(img, verbose=False)
            for r in results:
                names = getattr(r, "names", {}) or {}
                boxes = getattr(r, "boxes", None)
                if boxes is None or getattr(boxes, "cls", None) is None:
                    continue
                cls_values = boxes.cls.tolist()
                for cls_id in cls_values:
                    idx = int(cls_id)
                    name = names.get(idx, str(idx)) if isinstance(names, dict) else str(idx)
                    detected.append(str(name))
        except Exception:
            continue

    unique = []
    for d in detected:
        if d not in unique:
            unique.append(d)
    return unique


def main():
    args = parse_args()
    input_path = args.input_path
    output_path = args.output_path
    ext = os.path.splitext(input_path)[1].lower().strip(".")
    max_pages = max(1, int(args.max_pages))
    margin_min_ratio = max(0.005, float(args.margin_min_ratio))
    min_paragraphs = max(1, int(args.min_paragraphs))
    min_section_hits = max(1, int(args.min_section_hits))
    pass_score = max(1, min(100, int(args.pass_score)))

    required_class_list = [x.strip().lower() for x in str(args.required_classes or "").split(",") if x.strip()]

    result = {
        "supported": ext in {"docx", "pdf"},
        "model_used": args.model,
        "required_classes": args.required_classes,
        "ocr_lang": args.ocr_lang,
        "max_pages": max_pages,
        "pass_score_threshold": pass_score,
        "tesseract_cmd": "",
        "detected_classes": [],
        "format_passed": False,
        "pages_count": 0,
        "ocr_text_sample": "",
        "checks": {},
        "message": "",
    }

    checks = {}
    text_sample = ""
    media_images = []

    if ext == "docx":
        try:
            document_xml, settings_xml, styles_xml, media_blobs = extract_docx_xml(input_path)
            xml_checks, _ = checks_from_docx_xml(
                document_xml,
                settings_xml,
                styles_xml,
                min_paragraphs=min_paragraphs,
                allowed_fonts_csv=args.allowed_fonts,
            )
            checks.update(xml_checks)
            text_sample = plain_text_from_docx_xml(document_xml)

            mods = try_load_optional_modules()
            result["tesseract_cmd"] = configure_tesseract_command(mods, args.tesseract_cmd)
            media_images = images_from_docx_media(media_blobs, mods["cv2"], mods["np"], max_images=max_pages)
            result["pages_count"] = max(result["pages_count"], len(media_images))

            if media_images:
                ocr_text = ocr_text_from_images(media_images, mods["pytesseract"], args.ocr_lang, max_images=max_pages)
                if ocr_text:
                    text_sample = (text_sample + "\n" + ocr_text).strip()

                heading_check, structure_check = heading_structure_from_ocr(ocr_text, min_section_hits=min_section_hits)
                checks["heading"] = heading_check if not checks.get("heading", {}).get("passed", False) else checks["heading"]
                checks["structure"] = structure_check

                margin_check = detect_margin_from_ocr(media_images, mods["pytesseract"], args.ocr_lang, margin_min_ratio=margin_min_ratio)
                if margin_check is not None and not checks.get("margin", {}).get("passed", False):
                    checks["margin"] = margin_check

                result["detected_classes"] = yolo_detect(media_images, mods["YOLO"], args.model, max_images=max_pages)
        except Exception as exc:
            result["message"] = f"Gagal membaca DOCX: {exc}"

    elif ext == "pdf":
        mods = try_load_optional_modules()
        result["tesseract_cmd"] = configure_tesseract_command(mods, args.tesseract_cmd)
        pdf_images = images_from_pdf(input_path, mods["fitz"], mods["cv2"], mods["np"], max_pages=max_pages)
        page_analysis = collect_pdf_page_analysis(
            input_path,
            mods["fitz"],
            max_pages=max_pages,
            allowed_fonts_csv=args.allowed_fonts,
            min_section_hits=min_section_hits,
            margin_min_ratio=margin_min_ratio,
        )
        result["pages_count"] = page_analysis["pages_count"] or len(pdf_images)

        ocr_text = ""
        if page_analysis["ocr_fallback_needed"] and pdf_images:
            ocr_text = ocr_text_from_images(pdf_images, mods["pytesseract"], args.ocr_lang, max_images=max_pages)

        text_sample = page_analysis["text_sample"] or ocr_text
        if not text_sample:
            text_sample = ocr_text

        heading_check = page_analysis["ocr_heading"]
        structure_check = page_analysis["ocr_structure"]
        if ocr_text:
            ocr_heading_check, ocr_structure_check = heading_structure_from_ocr(ocr_text, min_section_hits=min_section_hits)
            if ocr_heading_check["passed"]:
                heading_check = ocr_heading_check
            if ocr_structure_check["passed"]:
                structure_check = ocr_structure_check

        checks["heading"] = heading_check
        checks["structure"] = structure_check
        checks["margin"] = bool_check(page_analysis["margin_passed"], page_analysis["margin_message"], "pdf")
        checks["paper_size"] = bool_check(page_analysis["page_size_passed"], page_analysis["page_size_message"], "pdf")
        checks["font"] = bool_check(page_analysis["font_passed"], page_analysis["font_message"], "pdf")

        if (not checks["margin"]["passed"]) and pdf_images:
            margin_check = detect_margin_from_ocr(pdf_images, mods["pytesseract"], args.ocr_lang, margin_min_ratio=margin_min_ratio)
            if margin_check is not None:
                checks["margin"] = margin_check

        result["detected_classes"] = yolo_detect(pdf_images, mods["YOLO"], args.model, max_images=max_pages)

    else:
        checks = {
            "heading": bool_check(False, "Format file tidak didukung untuk screening YOLO/OCR", "yolo_ocr"),
            "footer": bool_check(False, "Format file tidak didukung", "yolo_ocr"),
            "paper_size": bool_check(False, "Format file tidak didukung", "yolo_ocr"),
            "margin": bool_check(False, "Format file tidak didukung", "yolo_ocr"),
            "spacing_padding": bool_check(False, "Format file tidak didukung", "yolo_ocr"),
            "structure": bool_check(False, "Format file tidak didukung", "yolo_ocr"),
            "font": bool_check(False, "Format file tidak didukung", "yolo_ocr"),
        }

    required = ["heading", "footer", "paper_size", "margin", "spacing_padding", "structure", "font"]
    for key in required:
        if key not in checks:
            checks[key] = bool_check(False, "Pemeriksaan tidak tersedia", "yolo_ocr")

    passed_count = sum(1 for key in required if checks.get(key, {}).get("passed", False))
    score = int(round((passed_count / len(required)) * 100))

    detected_lc = [str(x).strip().lower() for x in result.get("detected_classes", []) if str(x).strip()]
    if required_class_list:
        missing = [cls for cls in required_class_list if cls not in detected_lc]
        checks["yolo_required_classes"] = bool_check(
            len(missing) == 0,
            "Semua class YOLO wajib terdeteksi." if len(missing) == 0 else "Class YOLO belum lengkap: " + ", ".join(missing),
            "yolo",
        )

    result["checks"] = checks
    result["format_passed"] = score >= pass_score
    result["score"] = score
    result["ocr_text_sample"] = (text_sample or "")[:500]
    result["detected_percentages"] = extract_percent_values(text_sample)

    if result["detected_percentages"]:
        result["message"] = (result["message"] + " " if result.get("message") else "") + (
            "Persentase terdeteksi: " + ", ".join(result["detected_percentages"]) + "."
        )

    if not result.get("message"):
        result["message"] = (
            "Screening YOLOv8 + OCR selesai."
            if result["supported"]
            else "Format file tidak didukung untuk screening YOLOv8 + OCR."
        )

    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    with open(output_path, "w", encoding="utf-8") as f:
        json.dump(result, f, ensure_ascii=False, indent=2)


if __name__ == "__main__":
    main()

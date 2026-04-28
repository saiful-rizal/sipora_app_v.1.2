<?php

return [
    'yolo_ocr_enabled' => env('DOCUMENT_SCREENING_YOLO_OCR_ENABLED', true),
    'python' => env('DOCUMENT_SCREENING_PYTHON', 'python'),
    'model' => env('DOCUMENT_SCREENING_MODEL', 'yolov8n.pt'),
    'ocr_lang' => env('DOCUMENT_SCREENING_OCR_LANG', 'eng+ind'),
    'required_classes' => env('DOCUMENT_SCREENING_REQUIRED_CLASSES', 'heading,title,paragraph'),
    'allowed_fonts' => env('DOCUMENT_SCREENING_ALLOWED_FONTS', 'Times New Roman,Calibri,Arial,Cambria'),
    'min_paragraphs' => (int) env('DOCUMENT_SCREENING_MIN_PARAGRAPHS', 5),
    'min_section_hits' => (int) env('DOCUMENT_SCREENING_MIN_SECTION_HITS', 3),
    'margin_min_ratio' => (float) env('DOCUMENT_SCREENING_MARGIN_MIN_RATIO', 0.02),
    'max_pages' => (int) env('DOCUMENT_SCREENING_MAX_PAGES', 3),
    'pass_score' => (int) env('DOCUMENT_SCREENING_PASS_SCORE', 70),
    'tesseract_cmd' => env('DOCUMENT_SCREENING_TESSERACT_CMD', ''),
    'timeout' => (int) env('DOCUMENT_SCREENING_TIMEOUT', 120),
];

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Dokumen - SIPORA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(180deg, #eef4ff 0%, #f8fafc 36%, #ffffff 100%);
            min-height: 100vh;
        }

        .edit-wrap {
            max-width: 1080px;
            margin: 0 auto;
            padding: 28px 18px 44px;
        }

        .edit-card {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 24px;
            box-shadow: 0 18px 60px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .edit-hero {
            padding: 28px 30px;
            background: linear-gradient(135deg, #1a56d6 0%, #6366f1 100%);
            color: #fff;
        }

        .edit-body {
            padding: 28px 30px 34px;
        }

        .edit-label {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .edit-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
    </style>
</head>

<body>
    @include('components.navbar')

    <div class="edit-wrap">
        <div class="edit-card">
            <div class="edit-hero">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                        style="width:52px;height:52px;">
                        <i class="bi bi-pencil-square fs-4"></i>
                    </div>
                    <div>
                        <h3 class="mb-1">Edit Dokumen</h3>
                        <div class="opacity-75">Perbarui metadata dokumen yang masih dapat diedit.</div>
                    </div>
                </div>
            </div>

            <div class="edit-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Terjadi kesalahan.</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('documents.update', $document->dokumen_id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="edit-label">Judul Dokumen</label>
                            <input type="text" name="judul" class="form-control form-control-lg"
                                value="{{ old('judul', $document->judul) }}" required>
                        </div>

                        <div class="col-12">
                            <label class="edit-label">Abstrak</label>
                            <textarea name="abstrak" class="form-control" rows="5">{{ old('abstrak', $document->abstrak) }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="edit-label">Kata Kunci</label>
                            <input type="text" name="kata_kunci" class="form-control"
                                value="{{ old('kata_kunci', $document->kata_kunci) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="edit-label">Divisi</label>
                            <select name="id_divisi" class="form-select" required>
                                @foreach ($divisi_data as $item)
                                    <option value="{{ $item->id_divisi }}"
                                        @selected((int) old('id_divisi', $document->id_divisi) === (int) $item->id_divisi)>
                                        {{ $item->nama_divisi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="edit-label">Jurusan</label>
                            <select name="id_jurusan" class="form-select" required>
                                @foreach ($jurusan_data as $item)
                                    <option value="{{ $item->id_jurusan }}"
                                        @selected((int) old('id_jurusan', $document->id_jurusan) === (int) $item->id_jurusan)>
                                        {{ $item->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="edit-label">Program Studi</label>
                            <select name="id_prodi" class="form-select" required>
                                @foreach ($prodi_data as $item)
                                    <option value="{{ $item->id_prodi }}"
                                        @selected((int) old('id_prodi', $document->id_prodi) === (int) $item->id_prodi)>
                                        {{ $item->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="edit-label">Tema</label>
                            <select name="id_tema" class="form-select" required>
                                @foreach ($tema_data as $item)
                                    <option value="{{ $item->id_tema }}"
                                        @selected((int) old('id_tema', $document->id_tema) === (int) $item->id_tema)>
                                        {{ $item->nama_tema }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="edit-label">Tahun Akademik</label>
                            <select name="year_id" class="form-select" required>
                                @foreach ($tahun_data as $item)
                                    <option value="{{ $item->year_id }}"
                                        @selected((int) old('year_id', $document->year_id) === (int) $item->year_id)>
                                        {{ $item->tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="edit-label">Turnitin (%)</label>
                            <input type="number" name="turnitin" class="form-control" min="0" max="100"
                                step="1" value="{{ old('turnitin', $document->turnitin ?? 0) }}">
                        </div>

                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                File dokumen tidak diubah pada halaman ini. Jika ingin mengganti file, silakan unggah ulang.
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="edit-actions mt-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-save2 me-2"></i>Simpan Perubahan
                                </button>
                                <a href="{{ route('documents.my') }}" class="btn btn-outline-secondary btn-lg">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('components.chatbot_widget')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
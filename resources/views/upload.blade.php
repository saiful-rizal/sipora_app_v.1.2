<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPORA | Upload Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body data-old-prodi="{{ old('id_prodi', $last_uploaded_document->id_prodi ?? '') }}" data-upload-prodi-endpoint="{{ route('upload.get-prodi') }}">
    <div class="bg-animation">
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
        <div class="bg-circle"></div>
    </div>

    @include('components.navbar')
    @include('components.header_upload')
    @include('components.top_menu')

    <div class="upload-container">
        @if(session('upload_success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <div><strong>Upload Berhasil!</strong> Dokumen Anda telah berhasil diunggah.</div>
            </div>
        @endif

        @if(!empty($screening_result))
            <div class="alert {{ ($screening_result['passed'] ?? false) ? 'alert-success' : 'alert-warning' }}">
                <i class="bi {{ ($screening_result['passed'] ?? false) ? 'bi-shield-check' : 'bi-exclamation-triangle' }}"></i>
                <div>
                    <strong>Hasil Screening Format ({{ $screening_result['score'] ?? 0 }}%)</strong>
                    <div>{{ $screening_result['message'] ?? '-' }}</div>
                    @if(!empty($screening_result['checks']) && is_array($screening_result['checks']))
                        <ul class="mb-0 mt-2">
                            @foreach($screening_result['checks'] as $name => $check)
                                <li>
                                    {{ ucfirst(str_replace('_', ' ', $name)) }}:
                                    {{ !empty($check['passed']) ? 'Lolos' : 'Belum Sesuai' }}
                                    @if(!empty($check['message'])) - {{ $check['message'] }} @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <strong>Error!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="upload-form-card">
            <div class="upload-form-header">
                <i class="bi bi-cloud-upload"></i>
                <h4>Form Unggah Dokumen</h4>
            </div>

            <form method="POST" action="{{ route('upload.store') }}" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Judul Dokumen <span class="required">*</span></label>
                        <input type="text" class="form-control" name="judul" required value="{{ old('judul') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="jenis_dokumen">Jenis Dokumen <span class="required">*</span></label>
                        <select class="form-control" id="jenis_dokumen" name="jenis_dokumen" required>
                            <option value="">-- Pilih Jenis Dokumen --</option>
                            <option value="laporan_magang" @selected(old('jenis_dokumen', $last_uploaded_document->jenis_dokumen ?? '') === 'laporan_magang')>Laporan Magang</option>
                            <option value="tugas_akhir" @selected(old('jenis_dokumen', $last_uploaded_document->jenis_dokumen ?? '') === 'tugas_akhir')>Tugas Akhir</option>
                            <option value="skripsi" @selected(old('jenis_dokumen', $last_uploaded_document->jenis_dokumen ?? '') === 'skripsi')>Skripsi</option>
                            <option value="tesis" @selected(old('jenis_dokumen', $last_uploaded_document->jenis_dokumen ?? '') === 'tesis')>Tesis</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="year_id">Tahun <span class="required">*</span></label>
                        <select class="form-control" id="year_id" name="year_id" required>
                            <option value="">-- Pilih Tahun --</option>
                            @foreach($tahun_data as $tahun)
                                <option value="{{ $tahun->year_id }}" @selected(old('year_id', $last_uploaded_document->year_id ?? null) == $tahun->year_id)>{{ $tahun->tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi Singkat <span class="required">*</span></label>
                    <textarea class="form-control" name="abstrak" required>{{ old('abstrak') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Kata Kunci <span class="required">*</span></label>
                    <input type="text" class="form-control" name="kata_kunci" required value="{{ old('kata_kunci') }}">
                    <small class="text-muted">Pisahkan dengan koma (contoh: machine learning, data mining, AI)</small>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Divisi <span class="required">*</span></label>
                        <select class="form-control" name="id_divisi" required>
                            <option value="">Pilih Divisi</option>
                            @foreach($divisi_data as $divisi)
                                <option value="{{ $divisi->id_divisi }}" @selected(old('id_divisi', $last_uploaded_document->id_divisi ?? null) == $divisi->id_divisi)>{{ $divisi->nama_divisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jurusan <span class="required">*</span></label>
                        <select class="form-control" name="id_jurusan" id="id_jurusan" required>
                            <option value="">Pilih Jurusan</option>
                            @foreach($jurusan_data as $jurusan)
                                <option value="{{ $jurusan->id_jurusan }}" @selected(old('id_jurusan', $last_uploaded_document->id_jurusan ?? null) == $jurusan->id_jurusan)>{{ $jurusan->nama_jurusan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Program Studi <span class="required">*</span></label>
                        <select class="form-control" name="id_prodi" id="id_prodi" required>
                            <option value="">Pilih Program Studi</option>
                            @foreach($prodi_data as $prodi)
                                <option value="{{ $prodi->id_prodi }}" @selected(old('id_prodi', $last_uploaded_document->id_prodi ?? null) == $prodi->id_prodi)>{{ $prodi->nama_prodi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tema <span class="required">*</span></label>
                        <select class="form-control" name="id_tema" required>
                            <option value="">Pilih Tema</option>
                            @foreach($tema_data as $tema)
                                <option value="{{ $tema->id_tema }}" @selected(old('id_tema', $last_uploaded_document->id_tema ?? null) == $tema->id_tema)>{{ $tema->nama_tema }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">File Dokumen <span class="required">*</span></label>
                    <input type="file" class="form-control" name="file_dokumen" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx" required>
                    <small class="text-muted">Format: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX (maks 10MB)</small>
                </div>

                <div class="optional-section">
                    <div class="optional-header">
                        <h5>Skor Turnitin</h5>
                        <span class="badge">Opsional</span>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Persentase Kemiripan</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="turnitin" min="0" max="100" step="0.1" value="{{ old('turnitin') }}">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">File Laporan Turnitin</label>
                            <input type="file" class="form-control" name="turnitin_file" accept=".pdf,.doc,.docx">
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary" name="upload_document"><i class="bi bi-cloud-upload"></i> Unggah Dokumen</button>
                    <button type="reset" class="btn btn-secondary"><i class="bi bi-arrow-clockwise"></i> Reset Form</button>
                </div>
            </form>
        </div>
    </div>

    @include('components.footer_upload')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/upload-page.js') }}"></script>
    <script src="{{ asset('assets/js/upload.js') }}"></script>
</body>
</html>

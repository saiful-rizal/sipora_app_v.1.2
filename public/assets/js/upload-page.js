(function () {
  function initUploadPage() {
    const oldProdi = document.body.dataset.oldProdi || '';
    const prodiEndpoint = document.body.dataset.uploadProdiEndpoint || '';

    if (typeof $ === 'undefined') return;

    $('#id_jurusan').on('change', function () {
      const idJurusan = $(this).val();
      const $prodiSelect = $('#id_prodi');
      $prodiSelect.html('<option value="">Memuat...</option>');

      if (idJurusan) {
        $.getJSON(`${prodiEndpoint}?id_jurusan=${idJurusan}`, function (data) {
          let options = '<option value="">-- Pilih Program Studi --</option>';
          data.forEach(function (item) {
            const selected = oldProdi && String(oldProdi) === String(item.id_prodi) ? 'selected' : '';
            options += `<option value="${item.id_prodi}" ${selected}>${item.nama_prodi}</option>`;
          });
          $prodiSelect.html(options);
        }).fail(function () {
          $prodiSelect.html('<option value="">Gagal memuat program studi</option>');
        });
      } else {
        $prodiSelect.html('<option value="">-- Pilih Program Studi --</option>');
      }
    });

    if ($('#id_jurusan').val()) {
      $('#id_jurusan').trigger('change');
    }
  }

  $(document).ready(initUploadPage);
})();
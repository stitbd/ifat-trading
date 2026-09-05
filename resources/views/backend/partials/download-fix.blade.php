{{--
    ==========================================================================
    FIX: Loading spinner stuck after Excel/PDF export download
    ==========================================================================
    Problem:
      - Your global layout shows the spinner on `beforeunload` and hides it
        on `load`. File downloads (Content-Disposition: attachment) never
        trigger a page reload, so `load` never fires again -> spinner stays
        forever.

    Fix:
      - Give every export/download link/button the class "js-download-btn"
      - Use fetch() + blob to actually download the file via JS, and hide
        the spinner in a .finally() block that always runs, regardless of
        success or failure.

    Usage in your Blade view:
      <a href="{{ route('requisition.export', $data->id) }}"
         class="btn-admin-primary js-download-btn"
         data-filename="Requisition_{{ $data->requisition_no }}.xlsx">
          <i class="bi bi-file-earmark-excel"></i> Export Excel
      </a>
    ==========================================================================
--}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.js-download-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const url = this.getAttribute('href');
                const fileName = this.getAttribute('data-filename') || 'download';

                // Show spinner manually (don't rely on beforeunload for this button)
                showLoading();

                fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    })
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('Download failed: ' + response.status);
                        }
                        return response.blob();
                    })
                    .then(function(blob) {
                        const link = document.createElement('a');
                        const objectUrl = window.URL.createObjectURL(blob);
                        link.href = objectUrl;
                        link.download = fileName;
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                        window.URL.revokeObjectURL(objectUrl);
                    })
                    .catch(function(error) {
                        console.error(error);
                        Toastify({
                            text: "Download failed. Please try again.",
                            duration: 4000,
                            backgroundColor: "red",
                            close: true,
                            gravity: "top",
                            position: "center",
                        }).showToast();
                    })
                    .finally(function() {
                        // Always hide the spinner, whether it succeeded or failed
                        hideLoading();
                    });
            });
        });
    });
</script>

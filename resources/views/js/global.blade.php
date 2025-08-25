<script>
    function isMobileDevice() {
        return window
            .matchMedia("only screen and (max-width: 760px)").matches;
    }

    $(document).ready(function () {
        $(document).on('click', '.detail_log', function () {
            livewire.emit('set_activity', $(this).attr('value'));
            $("#modal_log").modal('show');
        });

        $('.optional_new_tab').each(function () {
            if (isMobileDevice()) {
                $(this).removeAttr('target');
            } else {
                $(this).attr('target', '_blank');
            }
        });

        if (isMobileDevice()) {
            $("body").append('<button class="btn-reload btn-icon" onClick="window.location.reload(true)"><i class="bx bx-refresh"></i></button><button class="btn-back btn-icon" onClick="history.back()"><i class="bx bx-arrow-back"></i></button>');
        }
    });
</script>

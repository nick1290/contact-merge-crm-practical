<div class="modal fade" id="mergeContactModal" tabindex="-1" aria-labelledby="mergeContactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mergeContactModalLabel">Merge Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="secondary_id" id="secondary_id">
                    <div class="col-12">
                        <div class="mb-3">
                            <select name="master_id" id="master_id" required class="form-select"
                                aria-label="Default select example">
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label>Secondary Contact</label>
                            <input id="secondary_name" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnSaveMergeContact">Merge Contact</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btnMergeContact', function() {
                var secondaryId = $(this).data('id');
                var secondaryName = $(this).data('name');
                console.log(secondaryId);
                $('#secondary_id').val(secondaryId);
                $('#secondary_name').val(secondaryName);
                $.get("contact/master-data/" + secondaryId, function(data, textStatus, jqXHR) {
                    var html = "";
                    $.each(data, function(i, val) {
                        html += '<option value="' + val.id + '">' + val.name + '</option>'
                    });
                    $('#master_id').html(html);
                    $('#mergeContactModal').modal('show');
                });
            });
        });
    </script>
@endpush

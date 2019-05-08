<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalTitle"><i class="fas fa-plus-circle fa-lg fa-fw text-primary"></i>New</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="fullname" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="fullname" placeholder="Enter...">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="address" rows="3" placeholder="Enter..." style="resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="contact" class="col-sm-2 col-form-label">Contact</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="contact" placeholder="Enter...">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="income" class="col-sm-2 col-form-label">Income</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="income" placeholder="Enter...">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="age" class="col-sm-2 col-form-label">Age</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="age" placeholder="Enter...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="far fa-window-close fa-fw"></i>Close</button>
                    <button type="submit" class="btn btn-primary"><i class="far fa-save fa-fw"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
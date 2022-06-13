<div class="d-flex align-items-center justify-content-center vh-100">
    <form name="import_fund_statement"
          action="<?php echo $urlInterface->getBaseUrl() . '?action=import_fund_statement&show=analytics';?>"
          method="POST" enctype="multipart/form-data" class="p-5 border border-primary">
        <div class="mb-3">
            <label for="fileToUpload" class="form-label">Import Fund Statement CSV From Kite</label>
            <input class="form-control" type="file" name="fileToUpload" id="fileToUpload">
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
    </form>
</div>
<div class="card my-4 shadow">
    <div class="card-body">
        <form method="post" action="upload.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image">Загрузите презентацию</label>
                <input type="file" class="form-control-file" id="pptx" name="pptx" required>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Output select</label>
                <select class="form-control" id="output" name="output">
                    <option value="png">PNG</option>
                    <option value="jpg">JPG</option>
                    <option value="pdf">PDF</option>
                </select>
            </div>
            <div class="clearfix mt-4">
                <button type="submit" name="submit" class="btn btn-primary float-right text-uppercase shadow-sm">Отправить</button>
            </div>
        </form>
    </div>
</div>
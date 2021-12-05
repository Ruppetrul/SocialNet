<div id="post_data" {{--data-token="{{csrf_token()}}"--}} class="table-responsive">
    <table id="students_table" name="students_table" class="table table-hover table-striped">
        @csrf
        <tbody>
            <code lang="html">
                <div id="content"></div>
            </code>
        </tbody>
    </table>
    <div id="load_more">
        <button type="button" id="load_more_button" name="load_more_button" class="btn btn-info form-control">No Data Found</button>
    </div>
</div>

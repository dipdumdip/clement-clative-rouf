@extends('layout.main')

@section('content')
 <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-xs-12 col-md-5">.col-xs-12 .col-sm-6 .col-md-8</div>
        <div class="col-xs-12 col-md-7">
          <div id="dashboard_updates">
             <form role="form">
              <div class="form-group">
               <h4>What's up?</h4>
                <textarea class="form-control" rows="3"name="update_area" id="update_area" maxlength="400" ></textarea>
              </div>
               <button type="submit" class="btn btn-primary update_button">Submit</button>
                <button type="button" class="btn btn-default btn prev_photos_album pull-right">
                  <span class="glyphicon glyphicon-camera"></span> Images
                </button>
            </form>
            <div id="image_upload" class="hidden"> </div>
          </div>
          <div class="clearfix"></div>

          <div id="flashmessage" class="hidden"> </div>
      
          <div id="updatez_reloader" class="hidden" time="{{ time() }}" >
              <div class="realoder_info" tot=""></div>
          </div>
          <div id="update_loaders">
             @include('dashboard.comments_all')
            <div style="clear:both"></div>
          </div>
      </div>
    </div>
@stop
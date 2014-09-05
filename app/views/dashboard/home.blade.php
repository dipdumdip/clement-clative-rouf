@extends('layout.main')

@section('content')
 <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-xs-12 col-md-5">
        	<div class="row">
        	    <div class="row">
						  <div class="col-xs-12 col-sm-3 col-md-4 bg-warning">
							<img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNDAiIGhlaWdodD0iMTQwIj48cmVjdCB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjcwIiB5PSI3MCIgc3R5bGU9ImZpbGw6I2FhYTtmb250LXdlaWdodDpib2xkO2ZvbnQtc2l6ZToxMnB4O2ZvbnQtZmFtaWx5OkFyaWFsLEhlbHZldGljYSxzYW5zLXNlcmlmO2RvbWluYW50LWJhc2VsaW5lOmNlbnRyYWwiPjE0MHgxNDA8L3RleHQ+PC9zdmc+" style="width: 140px; height: 140px;" data-src="holder.js/140x140" class="img-thumbnail" alt="140x140">
						  </div>

					  <div class="col-xs-12 col-sm-9 col-md-8 bg-info">
						<h3>Varun Jose</h3>
						<address>
						  795 Folsom Ave, Suite 600<br>
						  San Francisco, CA 94107<br>
						  <abbr title="Phone">P:</abbr> (123) 456-7890
						</address>
					  </div>
				</div>
        		<div class="row bg-warning">
				  	<button type="button" class="btn btn-default btn-sm pull-right">
					  <span class="glyphicon glyphicon-bell"></span> Events
					</button>
				  	
				  	<button type="button" class="btn btn-default btn-sm pull-right">
					  <span class="glyphicon glyphicon-envelope"></span> Mail
					</button>
				  	
				  	<button type="button" class="btn btn-default btn-sm pull-right">
					  <span class="glyphicon glyphicon-wrench"></span> Settings
					</button>
				  	
				  	<button type="button" class="btn btn-default btn-sm pull-right">
					  <span class="glyphicon glyphicon-star"></span> Live
					</button>
				</div>
			</div>
			<div class="row">
				 <h4 class="bg-primary" style="padding:7px;width:auto;">Chair Invitation(s)</h4>
				  <div class="row bg-warning" style="padding:3px 0;">
				 	  <div class="col-xs-10 col-sm-10 col-md-9">chair requested from</div>
					  <div class="col-xs-2 col-sm-2 col-md-3 ">
						  <button type="button" class="btn btn-default btn-xs pull-right">No</button>
						  <button type="button" class="btn btn-primary btn-xs pull-right">Ok</button>
					  </div>
				   </div>
			</div>
        	<div class="row">
				<h4 class="bg-primary" style="padding:7px;width:auto;">Registered Companys</h4>
				<img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNDAiIGhlaWdodD0iMTQwIj48cmVjdCB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjcwIiB5PSI3MCIgc3R5bGU9ImZpbGw6I2FhYTtmb250LXdlaWdodDpib2xkO2ZvbnQtc2l6ZToxMnB4O2ZvbnQtZmFtaWx5OkFyaWFsLEhlbHZldGljYSxzYW5zLXNlcmlmO2RvbWluYW50LWJhc2VsaW5lOmNlbnRyYWwiPjE0MHgxNDA8L3RleHQ+PC9zdmc+" style="width: 140px; height: 140px;" data-src="holder.js/140x140" class="img-thumbnail" alt="140x140">
				<img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNDAiIGhlaWdodD0iMTQwIj48cmVjdCB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjcwIiB5PSI3MCIgc3R5bGU9ImZpbGw6I2FhYTtmb250LXdlaWdodDpib2xkO2ZvbnQtc2l6ZToxMnB4O2ZvbnQtZmFtaWx5OkFyaWFsLEhlbHZldGljYSxzYW5zLXNlcmlmO2RvbWluYW50LWJhc2VsaW5lOmNlbnRyYWwiPjE0MHgxNDA8L3RleHQ+PC9zdmc+" style="width: 140px; height: 140px;" data-src="holder.js/140x140" class="img-thumbnail" alt="140x140">
				<img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNDAiIGhlaWdodD0iMTQwIj48cmVjdCB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjcwIiB5PSI3MCIgc3R5bGU9ImZpbGw6I2FhYTtmb250LXdlaWdodDpib2xkO2ZvbnQtc2l6ZToxMnB4O2ZvbnQtZmFtaWx5OkFyaWFsLEhlbHZldGljYSxzYW5zLXNlcmlmO2RvbWluYW50LWJhc2VsaW5lOmNlbnRyYWwiPjE0MHgxNDA8L3RleHQ+PC9zdmc+" style="width: 140px; height: 140px;" data-src="holder.js/140x140" class="img-thumbnail" alt="140x140">
				<img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNDAiIGhlaWdodD0iMTQwIj48cmVjdCB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjcwIiB5PSI3MCIgc3R5bGU9ImZpbGw6I2FhYTtmb250LXdlaWdodDpib2xkO2ZvbnQtc2l6ZToxMnB4O2ZvbnQtZmFtaWx5OkFyaWFsLEhlbHZldGljYSxzYW5zLXNlcmlmO2RvbWluYW50LWJhc2VsaW5lOmNlbnRyYWwiPjE0MHgxNDA8L3RleHQ+PC9zdmc+" style="width: 140px; height: 140px;" data-src="holder.js/140x140" class="img-thumbnail" alt="140x140">
				<img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNDAiIGhlaWdodD0iMTQwIj48cmVjdCB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjcwIiB5PSI3MCIgc3R5bGU9ImZpbGw6I2FhYTtmb250LXdlaWdodDpib2xkO2ZvbnQtc2l6ZToxMnB4O2ZvbnQtZmFtaWx5OkFyaWFsLEhlbHZldGljYSxzYW5zLXNlcmlmO2RvbWluYW50LWJhc2VsaW5lOmNlbnRyYWwiPjE0MHgxNDA8L3RleHQ+PC9zdmc+" style="width: 140px; height: 140px;" data-src="holder.js/140x140" class="img-thumbnail" alt="140x140">
				<img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNDAiIGhlaWdodD0iMTQwIj48cmVjdCB3aWR0aD0iMTQwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjcwIiB5PSI3MCIgc3R5bGU9ImZpbGw6I2FhYTtmb250LXdlaWdodDpib2xkO2ZvbnQtc2l6ZToxMnB4O2ZvbnQtZmFtaWx5OkFyaWFsLEhlbHZldGljYSxzYW5zLXNlcmlmO2RvbWluYW50LWJhc2VsaW5lOmNlbnRyYWwiPjE0MHgxNDA8L3RleHQ+PC9zdmc+" style="width: 140px; height: 140px;" data-src="holder.js/140x140" class="img-thumbnail" alt="140x140">
        	</div>
        </div>
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
				<?php 	// Loading Messages
 					echo App::make('DashboardController')->updates();
				?>
				<div style="clear:both"></div>
		  </div>
      </div>
    </div>
@stop
  <div id="signupbox" style="{{ $active=='register' ? '' : 'display:none;'; }}margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title">Sign Up</div>
            <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#" onclick="$('#signupbox').hide();  $('#forgetbox').hide(); $('#loginbox').show(); return false;">Sign In</a></div>
        </div>  

        <div class="panel-body">
  		<form action="{{ URL::route('account-create-post') }}" method="post" accept-charset="utf-8" class="form-horizontal" id="signupform" role="form" autocomplete="off">
             <div id="signupalert" style="{{ count($errors)==0 ? 'display:none;' : '' }}" class="alert alert-danger">
                <p>
                    {{ $errors->has('email') ? $errors->first('email').'<br/>' : '' }}
                    {{ $errors->has('first_name') ? $errors->first('first_name').'<br/>' : '' }}
                    {{ $errors->has('last_name') ? $errors->first('last_name').'<br/>' : '' }}
                    {{ $errors->has('password') ? $errors->first('password').'<br/>' : '' }}
                    {{ $errors->has('password_confirm') ? $errors->first('password_confirm').'<br/>' : '' }}
                </p>
            </div>

            <div class="form-group">
                <label for="email" class="col-md-3 control-label">Email</label>
                <div class="col-md-9">
           	 		<input name="email" value="{{ Input::old('email') ? Input::old('email') : '' }}" id="email" class="form-control" placeholder="Email Address" type="text">
				</div>
            </div>
                
            <div class="form-group">
                <label for="firstname" class="col-md-3 control-label">First Name</label>
                <div class="col-md-9">
            <input name="first_name" value="{{ Input::old('first_name') ? Input::old('first_name') : '' }}" id="first_name" class="form-control" placeholder="First Name" type="text">                </div>
            </div>
            <div class="form-group">
                <label for="lastname" class="col-md-3 control-label">Last Name</label>
                <div class="col-md-9">
            <input name="last_name" value="{{ Input::old('last_name') ? Input::old('last_name') : '' }}" id="last_name" class="form-control" placeholder="Last Name" type="text">                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-md-3 control-label">Password</label>
                <div class="col-md-9">
            <input name="password" value="" id="password" class="form-control" placeholder="Password" type="password">                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-md-3 control-label">Confirm Password</label>
                <div class="col-md-9">
            <input name="password_confirm" value="" id="password_confirm" class="form-control" placeholder="Confirm Password" type="password">                </div>
            </div>
            <div class="form-group">
                <!-- Button -->                                        
                <div class="col-md-offset-3 col-md-9">
                 <input name="submit" value="&nbsp; Sign Up" id="btn-signup" class="btn btn-info" type="submit">     

                    <span style="margin-left:8px;">or</span>  
                </div>
            </div>
            
            <div style="border-top: 1px solid #999; padding-top:20px" class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <button id="btn-fbsignup" type="button" class="btn btn-primary"><i class="icon-facebook"></i>   Sign Up with Facebook</button>
                </div>                                           
            </div>
            {{ Form::token() }}
        </form> 
	</div>
    </div>
  </div> 
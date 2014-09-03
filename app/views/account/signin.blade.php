  <div id="loginbox" style="{{ $active=='login' ? '' : 'display:none;'; }}margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title">Sign In</div>
            <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#" onclick="$('#signupbox').hide();  $('#forgetbox').show(); $('#loginbox').hide(); return false;">Forgot password?</a></div>
        </div>     
        <div style="padding-top:30px" class="panel-body">
            <div id="login-alert" style="{{ count($errors)==0 && !isset($_GET['message']) ? 'display:none;' : '' }}" class="alert alert-danger">
                <p>
                    {{ $errors->has('email') ? $errors->first('email').'<br/>' : '' }}
                    {{ $errors->has('password') ? $errors->first('password').'<br/>' : '' }}
                    {{ isset($_GET['message']) ? $_GET['message'].'<br/>' : '' }}             
                </p>
            </div>
       <form action="{{ URL::route('account-signin') }}" method="post" accept-charset="utf-8" class="form-horizontal" id="signupform" role="form" autocomplete="off">
               <div style="clear:both;margin-bottom: 25px" class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                  <input name="email" value="{{ Input::old('email') ? Input::old('email') : '' }}" id="email" class="form-control" placeholder="username or email" type="text">
                </div>
                            
            <div style="margin-bottom: 25px" class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                  <input name="password" value="" id="password" class="form-control" placeholder="password" type="password">
            </div>
            <div class="input-group">
                  <div class="checkbox">
                    <label>
                  <input name="remember" value="1" id="remember" type="checkbox"> Remember me
                    </label>
                  </div>
            </div>
            <div style="margin-top:10px" class="form-group">
                <!-- Button -->
                <div class="col-sm-12 controls">
               <input name="submit" value="Login" id="btn-login" class="btn btn-success" type="submit">     
                <a id="btn-fblogin" href="#" class="btn btn-primary">Login with Facebook</a>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12 control">
                    <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%">
                        Don't have an account! 
                    <a href="#" onclick="$('#loginbox').hide(); $('#forgetbox').hide(); $('#signupbox').show(); return false;">
                        Sign Up Here
                    </a>
                    </div>
                </div>
            </div>
              {{ Form::token() }}    
          </form> 
      </div>                     
    </div>  
  </div>
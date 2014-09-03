@extends('layout.main')

@section('content')
<div class="row" style="margin-top:50px;">    
  <div id="ChangePassbox" style="margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title">Change Password</div>
        </div>  

        <div class="panel-body">
  		<form action="{{ URL::route('account-password-reset-post') }}" method="post" accept-charset="utf-8" class="form-horizontal" id="signupform" role="form" autocomplete="off">
             <div id="signupalert" style="{{ count($errors)==0 ? 'display:none;' : '' }}" class="alert alert-danger">
                <p>
                    {{ $errors->has('password') ? $errors->first('password').'<br/>' : '' }}
                    {{ $errors->has('password_confirm') ? $errors->first('password_confirm').'<br/>' : '' }}
                </p>
            </div>

            <div class="form-group">
                <label for="email" class="col-md-3 control-label">Email</label>
                <div class="col-md-9">
                    <input name="email" value="{{ $email }}" id="email" class="form-control" disabled="true" type="text">
				</div>
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
                 <input name="submit" value="&nbsp;Submit" id="btn-signup" class="btn btn-info" type="submit">     
                </div>
            </div>
                    <input name="email_hidden" value="{{ $email }}" type="hidden">
                    <input name="code_hidden" value="{{ $code }}" type="hidden">
            {{ Form::token() }}
        </form> 
	</div>
    </div>
  </div> 
</div> 
@stop
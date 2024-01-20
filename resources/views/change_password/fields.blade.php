<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group has-feedback{{ $errors->has('oldpassword') ? ' has-error' : '' }}">

                <label for="oldpassword"> &nbsp;&nbsp;&nbsp;Current Password</label>


                <input type="password" class="form-control" tabindex="1" placeholder="Current Password" id="oldpassword"
                       name="oldpassword" value="{{old('oldpassword')}}">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>


                @if ($errors->has('oldpassword'))
                    <span class="help-block">
                          <strong>{{ $errors->first('oldpassword') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group has-feedback{{ $errors->has('newpassword') ? ' has-error' : '' }}">

                <label for="newpassword">&nbsp;&nbsp;&nbsp; New Password</label>


                <input type="password" class="form-control"  tabindex="2" placeholder="New Password" id="newpassword"
                       name="newpassword" value="{{old('newpassword')}}">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>


                @if ($errors->has('newpassword'))
                    <span class="help-block">
                          <strong>{{ $errors->first('newpassword') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group has-feedback{{ $errors->has('repassword') ? ' has-error' : '' }}">

                <label for="re-password">&nbsp;&nbsp;&nbsp; Retype New Password</label>


                <input type="password" class="form-control"  tabindex="3" placeholder="Retype New Password" id="repassword"
                       name="repassword">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>


                @if ($errors->has('repassword'))
                    <span class="help-block">
                          <strong>{{ $errors->first('repassword') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="col-md-4">
                {!! Form::submit('Change', ['class' => 'btn btn-submit','tabindex'=>"4"]) !!}
            </div>
        </div>
    </div>
</div>



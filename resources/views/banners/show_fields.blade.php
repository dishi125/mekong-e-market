<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $banner->name }}</p>
</div>

<!-- Contact Field -->
<div class="form-group">
    {!! Form::label('contact', 'Contact:') !!}
    <p>{{ $banner->contact }}</p>
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('email', 'Email:') !!}
    <p>{{ $banner->email }}</p>
</div>

<!-- Start Date Field -->
<div class="form-group">
    {!! Form::label('start_date', 'Start Date:') !!}
    <p>{{ $banner->start_date }}</p>
</div>

<!-- Banner Link Field -->
<div class="form-group">
    {!! Form::label('banner_link', 'Banner Link:') !!}
    <p>{{ $banner->banner_link }}</p>
</div>

<!-- Banner Photo Field -->
<div class="form-group">
    {!! Form::label('banner_photo', 'Banner Photo:') !!}
    <p>{{ $banner->banner_photo }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $banner->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $banner->updated_at }}</p>
</div>


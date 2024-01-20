<!-- Main Category Id Field -->
<div class="form-group">
    {!! Form::label('main_category_id', 'Main Category Id:') !!}
    <p>{{ $specie->main_category_id }}</p>
</div>

<!-- Sub Category Id Field -->
<div class="form-group">
    {!! Form::label('sub_category_id', 'Sub Category Id:') !!}
    <p>{{ $specie->sub_category_id }}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $specie->name }}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{{ $specie->status }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $specie->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $specie->updated_at }}</p>
</div>


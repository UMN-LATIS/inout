<div class="form-group {{ $errors->has('unit') ? 'has-error' : ''}}">
    <label for="unit" class="col-md-4 control-label">{{ 'Unit' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="unit" type="text" id="unit" value="{{ $board->unit or ''}}" >
        {!! $errors->first('unit', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('public_title') ? 'has-error' : ''}}">
    <label for="public_title" class="col-md-4 control-label">{{ 'Public Title' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="public_title" type="text" id="public_title" value="{{ $board->public_title or ''}}" >
        {!! $errors->first('public_title', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('public') ? 'has-error' : ''}}">
    <label for="public" class="col-md-4 control-label">{{ 'Public' }}</label>
    <div class="col-md-6">
        <div class="radio">
    <label><input name="{{ public }}" type="radio" value="1" {{ (isset($board) && 1 == $board->public) ? 'checked' : '' }}> Yes</label>
</div>
<div class="radio">
    <label><input name="{{ public }}" type="radio" value="0" @if (isset($board)) {{ (0 == $board->public) ? 'checked' : '' }} @else {{ 'checked' }} @endif> No</label>
</div>
        {!! $errors->first('public', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('anyone_can_edit') ? 'has-error' : ''}}">
    <label for="anyone_can_edit" class="col-md-4 control-label">{{ 'Anyone Can Edit' }}</label>
    <div class="col-md-6">
        <div class="radio">
    <label><input name="{{ anyone_can_edit }}" type="radio" value="1" {{ (isset($board) && 1 == $board->anyone_can_edit) ? 'checked' : '' }}> Yes</label>
</div>
<div class="radio">
    <label><input name="{{ anyone_can_edit }}" type="radio" value="0" @if (isset($board)) {{ (0 == $board->anyone_can_edit) ? 'checked' : '' }} @else {{ 'checked' }} @endif> No</label>
</div>
        {!! $errors->first('anyone_can_edit', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Create' }}">
    </div>
</div>

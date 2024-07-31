<div class="form-group">
    <label for="name">Category Name</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="Category Name" required autofocus value="{{ old('category', $category->name ?? '') }}">
</div>
@error('name')
<div class="invalid-feedback">
  {{ $message }}
</div>
@enderror

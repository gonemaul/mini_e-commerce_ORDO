<style>
    .form-control:focus{
        color: #dddddd;
    }
    select.form-control{
        color: #dddddd;
    }
    .form-group {
        margin-top: 1rem;
        margin-bottom: 0;
    }
    .invalid-feedback {
        display: block !important;
    }
</style>
<div class="form-group">
    <label for="name">Category Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Category Name" required autofocus value="{{ old('category', $category->name ?? '') }}">
</div>
@error('name')
    <div class="invalid-feedback">
    {{ $message }}
    </div>
@enderror

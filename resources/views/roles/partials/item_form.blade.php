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
    .form-check{
        margin-left: 1.3rem;
    }
</style>
<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label for="name">{{ __('roles.label.name') }} <span class="text-danger"> *</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name ?? '') }}" placeholder="{{ __('roles.label.name') }}" required autofocus>
        </div>
        @error('name')
            <div class="invalid-feedback">
            {{ $message }}
            </div>
        @enderror
    </div>
    <input type="hidden" id="permissions" name="permissions" value="{{ old('permissions', json_encode($permissions)) }}">
    <div class="col-md-7 pl-5">
        <label for="">{{ __('roles.title.permissions') }} <span class="text-danger"> *</span></label>
        <div class="form-check-primary form-check">
            <label class="form-check-label">
            <input type="checkbox" class="form-check-input" id="all" name="all"> {{ __('roles.permissions.all') }}  </label>
        </div>
        <div class="form-group pl-4">
            <label for="">{{ __('roles.permissions.users') }}</label>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('user_view') ? 'checked' : '' }} id="user_view"> {{ __('roles.permissions.view') }}  </label>
            </div>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('user_detail') ? 'checked' : '' }} id="user_detail"> {{ __('roles.permissions.view_detail') }}  </label>
            </div>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" id="user_export" {{ $permissions->contains('user_export')  ? 'checked' : '' }}> {{ __('roles.permissions.export') }}  </label>
            </div>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" id="assign_roles" {{ $permissions->contains('assign_roles') ? 'checked' : '' }}> {{ __('roles.permissions.assign') }} </label>
            </div>
        </div>
        <div class="form-group pl-4">
            <label for="">{{ __('roles.permissions.categories') }}</label>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('category_view') ? 'checked' : '' }} id="category_view"> {{ __('roles.permissions.view') }}  </label>
            </div>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('category_create') ? 'checked' : '' }} id="category_create"> {{ __('roles.permissions.create') }}  </label>
            </div>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('category_edit') ? 'checked' : '' }} id="category_edit"> {{ __('roles.permissions.edit') }}  </label>
            </div>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('category_delete') ? 'checked' : '' }} id="category_delete"> {{ __('roles.permissions.delete') }}  </label>
            </div>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('category_exim') ? 'checked' : '' }} id="category_exim"> {{ __('roles.permissions.exim') }}  </label>
            </div>
        </div>
        <div class="form-group pl-4">
            <label for="">{{ __('roles.permissions.products') }}</label>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('product_view') ? 'checked' : '' }} id="product_view"> {{ __('roles.permissions.view') }}  </label>
            </div>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('product_create') ? 'checked' : '' }} id="product_create"> {{ __('roles.permissions.create') }}  </label>
            </div>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('product_edit') ? 'checked' : '' }} id="product_edit"> {{ __('roles.permissions.edit') }}  </label>
            </div>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('product_delete') ? 'checked' : '' }} id="product_delete"> {{ __('roles.permissions.delete') }}  </label>
            </div>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('product_exim') ? 'checked' : '' }} id="product_exim"> {{ __('roles.permissions.exim') }} </label>
            </div>
        </div>
        <div class="form-group pl-4">
            <label for="">{{ __('roles.permissions.orders') }}</label>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('order_view') ? 'checked' : '' }} id="order_view"> {{ __('roles.permissions.view') }} </label>
            </div>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('order_view_detail') ? 'checked' : '' }} id="order_view_detail"> {{ __('roles.permissions.view_detail') }} </label>
            </div>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('order_export') ? 'checked' : '' }} id="order_export"> {{ __('roles.permissions.export') }} </label>
            </div>
            <div class="form-check-primary form-check">
                <label class="form-check-label">
                <input type="checkbox" class="form-check-input check_permission" {{ $permissions->contains('order_update') ? 'checked' : '' }} id="order_update"> {{ __('roles.permissions.update_status') }} </label>
            </div>
        </div>
    </div>
</div>
<script>
    var permissions = [];
    var all = false;
    permissions = JSON.parse($('#permissions').val());
    $('.check_permission').each(function() {
        const allChecked = $('.check_permission').length === $('.check_permission:checked').length;
        $('#all').prop('checked', allChecked);
    });
    $('#all').change(function(){
        if (this.checked) {
            $('.check_permission').prop('checked', true);
        } else {
            $('.check_permission').prop('checked', false);
            permissions = [];
            // $('.check_permission').each(function() {
            //     if (permissions.includes($(this).attr('id'))) {
            //         $(this).prop('checked', true);
            //     } else {
            //         $(this).prop('checked', false);
            //     }
            // });
        }
        console.log(permissions)
        $('#permissions').val(JSON.stringify(permissions));
    })

    $('.check_permission').change(function() {
        var permission = $(this).attr('id');

        if ($(this).is(':checked')) {
            if (!permissions.includes(permission)) {
                permissions.push(permission);
            }
        } else {
            var index = permissions.indexOf(permission);
            if (index !== -1) {
                permissions.splice(index, 1);
            }
        }

        $('#permissions').val(JSON.stringify(permissions));
    }
);
</script>

@if (session()->has('success'))
<div class="alert alert-success" role="alert">
    <button aria-label="Close" class="close" data-bs-dismiss="alert" type="button">
        <span aria-hidden="true">&times;</span>
    </button>

    {{ session()->get('success') }}
</div>
@endif

@if (session()->has('error'))
<div class="alert alert-danger" role="alert">
    <button aria-label="Close" class="close" data-bs-dismiss="alert" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    {{ session()->get('error') }}
</div>
@endif

<x-app-layout page-title="My Informations">
    <div class="container">
        <div class="col-xl-4 col-lg-6 col-md-8 py-9 mx-auto">
            <form action="{{ route('user.update') }}" method="post">
                @csrf
                @method('put')

                @if ($errors->any())
                    <div class="row justify-content-center mb-3">
                        <div class="card col-6">
                            <div class="card-body">
                                @include('includes.validation-form')
                            </div>
                        </div>
                    </div>
                @endif
                {{-- {{ dd($countries) }} --}}
                <x-user-info-main :edit="true" :user="auth()->user()" :countries="$countries" :shipping="$shipping" :billing="$billing" />
                <div class="d-flex justify-content-end mt-3">
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-outline-primary">Edit my informations</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

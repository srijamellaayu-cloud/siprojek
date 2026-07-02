@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="font-semibold text-dark mb-0" style="font-weight: 800; color: #172b42;">{{ __('Profile') }}</h4>
            <small class="text-muted">Kelola detail akun Anda, ubah kata sandi, dan pengaturan privasi.</small>
        </div>
    </div>

    <div class="row g-4">
        <!-- Update Profile Info -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100" style="border-radius: 12px; border: 1px solid #dce4ef; box-shadow: 0 2px 8px rgba(28, 52, 83, 0.08) !important; overflow: hidden;">
                <div class="card-header py-3" style="background: #f8fafd; border-bottom: 1px solid #e7edf6;">
                    <h5 class="mb-0 text-dark" style="font-weight: 700; font-size: 1rem; color: #172b42;">Informasi Profil</h5>
                </div>
                <div class="card-body p-4">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Password -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100" style="border-radius: 12px; border: 1px solid #dce4ef; box-shadow: 0 2px 8px rgba(28, 52, 83, 0.08) !important; overflow: hidden;">
                <div class="card-header py-3" style="background: #f8fafd; border-bottom: 1px solid #e7edf6;">
                    <h5 class="mb-0 text-dark" style="font-weight: 700; font-size: 1rem; color: #172b42;">Perbarui Kata Sandi</h5>
                </div>
                <div class="card-body p-4">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="col-12">
            <div class="card shadow-sm" style="border-radius: 12px; border: 1px solid #dce4ef; box-shadow: 0 2px 8px rgba(28, 52, 83, 0.08) !important; overflow: hidden;">
                <div class="card-header py-3" style="background: #fdf2f2; border-bottom: 1px solid #fce8e6;">
                    <h5 class="mb-0 text-danger" style="font-weight: 700; font-size: 1rem;">Hapus Akun</h5>
                </div>
                <div class="card-body p-4">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
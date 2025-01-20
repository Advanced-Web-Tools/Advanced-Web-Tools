@section('head')
<link rel="stylesheet" href="@asset('css/accounts.css')">
<script type="module" src="@asset('js/AccountManager.js')"></script>
@endsection
@extends('Dashboard.views.templates.main')
@section('page')
<div class="account_list shadow">
    <h3>Other accounts</h3>
    @foreach($accounts as $account)
    <a class="account hp_primary" href="/dashboard/accounts/%account.id%">
        <h3 class="account_username">
            {{$account->username}}
        </h3>
        <p class="full_name">
            {{$account->firstname}} {{$account->lastname}}
        </p>
    </a>
    @endforeach
    @if($admin->permission_level == '0' || $admin->permission_level == '1')
        <button class="btn_primary" id="add_account" style="margin: 0 auto">Create account</button>
    @endif
</div>
<div class="profile">
    <div class="header">
        <img src="/{{ $profile->profile_picture }}" alt="" class="profile_picture">
        <div class="wrapper">
            <h3 class="fullname">
                {{$profile->firstname}} {{$profile->lastname}}
            </h3>
            <p class="role">
                {{$profile->role}}
            </p>
        </div>
        <div class="actions">
            <a href="mailto:%profile.email%" class="hp_primary">
                <button class="btn_secondary">Send email</button>
            </a>
            @php if ($admin->checkPermission($profile->permission_level) && $admin->permission_level <= 1 || $profile->id === $admin->id): @endphp
                <a href="/dashboard/account_manager/delete/%profile.id%">
                    <button class="btn_action_negative">Delete account</button>
                </a>
            @php endif; @endphp
        </div>
    </div>
    <div class="information">
        @if( $admin->permission_level == '1' || $admin->permission_level == '0' )
        <div class="info_container">
            <h3>Last login location:</h3>
            <p>{{ $profile->last_logged_ip }}</p>
        </div>
        @endif
        <div class="info_container">
            <h3>Role:</h3>
            <p>{{ $profile->role }}</p>
        </div>
        @if( $admin->id == $profile->id)
        <form class="password_change" method="post" action="/dashboard/account_manager/change_password">
            <h3>Change password</h3>
            <label>
                Current password:
                <input class="inp_primary lg" type="password" name="current_password" placeholder="Ex. Password1"
                       required>
            </label>
            <label>
                New password:
                <input class="inp_primary lg" type="password" name="new_password" placeholder="Ex. Password123!"
                       required>
            </label>
            <button class="btn_primary" type="submit">Change</button>
        </form>
        @endif
        @if($admin->id == $profile->id)
        <form class="email_change" action="/dashboard/account_manager/change_email" method="post">
            <h3>Change email</h3>
            <label>
                New email:
                <input class="inp_primary lg" type="email" name="email" placeholder="Email address" required>
            </label>
            <button class="btn_primary" type="submit">Change</button>
        </form>
        @endif
    </div>
</div>
@if($admin->permission_level == '1' || $admin->permission_level == '0' )

<div class="helper">
    <div class="content"></div>
</div>

@endif


@endsection
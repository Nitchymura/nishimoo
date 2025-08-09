@if (!$section)
    @if($user->role_id == 1)
        <ul class="nav nav-tabs custom-tabs mt-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link mx-4 {{ $tab == 'quests' ? 'active-tab' : '' }}"
                    href="{{ route('profile.header', ['id' => $user->id, 'tab' => 'quests']) }}">
                    Quests
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link mx-4 {{ $tab == 'spots' ? 'active-tab' : '' }}"
                    href="{{ route('profile.header', ['id' => $user->id, 'tab' => 'spots']) }}">
                    Spots
                </a>
            </li>
        </ul>
    @elseif(($user->role_id == 2  || $user->role_id == 3))
        <ul class="nav nav-tabs custom-tabs-black mt-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link mx-4 {{ $tab == 'quests' ? 'active-tab' : '' }}"
                    href="{{ route('profile.header', ['id' => $user->id, 'tab' => 'quests']) }}">
                    Travels
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link mx-4 {{ $tab == 'businesses' ? 'active-tab' : '' }}"
                    href="{{ route('profile.header', ['id' => $user->id, 'tab' => 'businesses']) }}">
                    Favorite
                </a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link mx-4 {{ $tab == 'promotions' ? 'active-tab' : '' }}"
                    href="{{ route('profile.header', ['id' => $user->id, 'tab' => 'promotions']) }}">
                    Promotions
                </a>
            </li> --}}

        </ul>
    @endif
@endif
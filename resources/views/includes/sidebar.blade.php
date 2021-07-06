<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Fresh Backend</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('home') }}">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Tableau de board</div>
            </a>
        </li>
        <li class="menu-label">Gestion utilisateurs</li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-user-circle'></i>
                </div>
                <div class="menu-title">Users</div>
            </a>
            <ul>
                <li>
                    <a href="#"><i class="bx bx-right-arrow-alt"></i>Créer un User</a>
                </li>
                <li>
                    <a href="#"><i class="bx bx-right-arrow-alt"></i>Liste des Users</a>
                </li>
            </ul>
        </li>
        <li class="menu-label">Configuration</li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-user-check'></i>
                </div>
                <div class="menu-title">Rôles</div>
            </a>
            <ul>
                <li>
                    <a href="#"><i class="bx bx-right-arrow-alt"></i>Créer un rôle</a>
                </li>
                <li>
                    <a href="#"><i class="bx bx-right-arrow-alt"></i>Liste des rôles</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-user-x'></i>
                </div>
                <div class="menu-title">Permissions</div>
            </a>
            <ul>
                <li>
                    <a href="#"><i class="bx bx-right-arrow-alt"></i>Créer une permission</a>
                </li>
                <li>
                    <a href="#"><i class="bx bx-right-arrow-alt"></i>Liste des permissions</a>
                </li>
            </ul>
        </li>
    </ul>
    <!--end navigation-->
</div>

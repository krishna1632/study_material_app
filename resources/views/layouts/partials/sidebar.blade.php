<nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">

            <!-- Dashboard Link -->
            <a class="nav-link" href="{{ url('admin/dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>

            <!-- Faculty Link -->
            <a class="nav-link" href="{{ url('admin/faculty') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                Faculty
            </a>

            <div class="sb-sidenav-menu-heading">Access Management</div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                data-bs-target="#collapseAccessManagement" aria-expanded="false"
                aria-controls="collapseAccessManagement">
                <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>
                Permissions & Roles
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>

            <div class="collapse" id="collapseAccessManagement" aria-labelledby="headingAccessManagement"
                data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    @can('view permissions')
                        <a class="nav-link" href="{{ route('permissions.index') }}">
                            <i class="fas fa-key"></i> Permissions
                        </a>
                    @endcan
                    @can('view roles')
                        <a class="nav-link" href="{{ route('roles.index') }}">
                            <i class="fas fa-user-tag"></i> Roles
                        </a>
                    @endcan
                    @can('view users')
                        <a class="nav-link" href="{{ route('users.list') }}">
                            <i class="fas fa-users"></i> Users
                        </a>
                    @endcan
                </nav>
            </div>

            <!-- Study Mast Section -->
            <div class="sb-sidenav-menu-heading">Study Mast</div>
            <a class="nav-link" href="{{ url('admin/upload_pyq') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-upload"></i></div>
                Upload PYQ
            </a>
            <a class="nav-link" href="{{ url('admin/study_materials') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                Study Materials
            </a>
            <a class="nav-link" href="{{ url('admin/roadmaps') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-road"></i></div>
                RoadMaps
            </a>
            <a class="nav-link" href="{{ url('admin/syllabus') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                Syllabus
            </a>

            <!-- Access Management Section -->



            <!-- Quiz Mast Section -->
            <div class="sb-sidenav-menu-heading">More Actions</div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseQuizMast"
                aria-expanded="false" aria-controls="collapseQuizMast">
                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                Quiz Mast
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseQuizMast" aria-labelledby="headingQuizMast"
                data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ url('admin/quiz/create') }}">Create Quiz</a>
                    <a class="nav-link" href="{{ url('admin/quiz/reports') }}">Quiz Reports</a>
                </nav>
            </div>

        </div>
    </div>
</nav>

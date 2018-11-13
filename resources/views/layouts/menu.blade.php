<li class="{{ Request::is('pages*') ? 'active' : '' }}">
    <a href="{!! route('pages.index') !!}"><i class="fa fa-edit"></i><span>Pages</span></a>
</li>

<li class="{{ Request::is('profiles*') ? 'active' : '' }}">
    <a href="{!! route('profiles.index') !!}"><i class="fa fa-edit"></i><span>Profiles</span></a>
</li>

<li class="{{ Request::is('jobs*') ? 'active' : '' }}">
    <a href="{!! route('jobs.index') !!}"><i class="fa fa-edit"></i><span>Jobs</span></a>
</li>

<li class="{{ Request::is('groups*') ? 'active' : '' }}">
    <a href="{!! route('groups.index') !!}"><i class="fa fa-edit"></i><span>Groups</span></a>
</li>

<li class="{{ Request::is('contacts*') ? 'active' : '' }}">
    <a href="{!! route('contacts.index') !!}"><i class="fa fa-edit"></i><span>Contacts</span></a>
</li>

<li class="{{ Request::is('contacts*') ? 'active' : '' }}">
    <a href="{!! route('contacts.index') !!}"><i class="fa fa-edit"></i><span>Contacts</span></a>
</li>

<li class="{{ Request::is('companies*') ? 'active' : '' }}">
    <a href="{!! route('companies.index') !!}"><i class="fa fa-edit"></i><span>Companies</span></a>
</li>
<li class="{{ Request::is('userCompanies*') ? 'active' : '' }}">
    <a href="{!! route('userCompanies.index') !!}"><i class="fa fa-edit"></i><span>User Companies</span></a>
</li>

<li class="{{ Request::is('groupUsers*') ? 'active' : '' }}">
    <a href="{!! route('groupUsers.index') !!}"><i class="fa fa-edit"></i><span>Group Users</span></a>
</li>

<li class="{{ Request::is('groupJobs*') ? 'active' : '' }}">
    <a href="{!! route('groupJobs.index') !!}"><i class="fa fa-edit"></i><span>Group Jobs</span></a>
</li>

<li class="{{ Request::is('jobUsers*') ? 'active' : '' }}">
    <a href="{!! route('jobUsers.index') !!}"><i class="fa fa-edit"></i><span>Job Users</span></a>
</li>


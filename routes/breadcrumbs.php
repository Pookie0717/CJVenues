<?php

use App\Models\User;
use App\Models\Quote;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Spatie\Permission\Models\Role;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('dashboard'));
});

// Home > Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Dashboard', route('dashboard'));
});

// Home > Dashboard > User Management
Breadcrumbs::for('user-management.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('User Management', route('user-management.users.index'));
});

// Home > Dashboard > User Management > Users
Breadcrumbs::for('user-management.users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Users', route('user-management.users.index'));
});

// Home > Dashboard > User Management > Users > [User]
Breadcrumbs::for('user-management.users.show', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('user-management.users.index');
    $trail->push(ucwords($user->name), route('user-management.users.show', $user));
});

// Home > Dashboard > User Management > Roles
Breadcrumbs::for('user-management.roles.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Roles', route('user-management.roles.index'));
});

// Home > Dashboard > User Management > Roles > [Role]
Breadcrumbs::for('user-management.roles.show', function (BreadcrumbTrail $trail, Role $role) {
    $trail->parent('user-management.roles.index');
    $trail->push(ucwords($role->name), route('user-management.roles.show', $role));
});

// Home > Dashboard > User Management > Permission
Breadcrumbs::for('user-management.permissions.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Permissions', route('user-management.permissions.index'));
});

// Home > Contacts
Breadcrumbs::for('contacts', function ($trail) {
    $trail->parent('home');
    $trail->push('Contacts', route('contacts'));
});

// Home > Bookings
Breadcrumbs::for('bookings', function ($trail) {
    $trail->parent('home');
    $trail->push('Bookings', route('bookings'));
});

// Home > Prices
Breadcrumbs::for('prices', function ($trail) {
    $trail->parent('home');
    $trail->push('Prices', route('prices'));
});

// Home > Staff
Breadcrumbs::for('staff', function ($trail) {
    $trail->parent('home');
    $trail->push('Staff', route('staff'));
});

// Home > Quotes
Breadcrumbs::for('quotes', function ($trail) {
    $trail->parent('home');
    $trail->push('Quotes', route('quotes')); 
});

// Home > Quotes > [Quote]
Breadcrumbs::for('quotes.show', function (BreadcrumbTrail $trail, Quote $quote) {
    $trail->parent('quotes'); // Refers to the 'quotes' breadcrumb defined earlier
    $trail->push($quote->quote_number.'.'.$quote->version, route('quotes.show', $quote));
});

// Home > Venues
Breadcrumbs::for('venues', function ($trail) {
    $trail->parent('home');
    $trail->push('Venues', route('venues'));
});

// Home > Areas
Breadcrumbs::for('areas', function ($trail) {
    $trail->parent('home');
    $trail->push('Areas', route('areas'));
});

// Home > Seasons
Breadcrumbs::for('seasons', function ($trail) {
    $trail->parent('home');
    $trail->push('Seasons', route('seasons'));
});


// Home > Options
Breadcrumbs::for('options', function ($trail) {
    $trail->parent('home');
    $trail->push('Options', route('options'));
});

// Home > Event Types
Breadcrumbs::for('event-types', function ($trail) {
    $trail->parent('home');
    $trail->push('Events Packages', route('event-types'));
});

// Home > Organizations
Breadcrumbs::for('tenants', function ($trail) {
    $trail->parent('home');
    $trail->push('Organizations', route('organizations'));
});

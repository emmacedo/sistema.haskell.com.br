{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> Dashboard</a></li>

{{-- Seção de Distribuidores --}}
<x-backpack::menu-dropdown title="Distribuidores" icon="la la-building">
    <x-backpack::menu-dropdown-item title="Listar Todos" icon="la la-list" :link="backpack_url('distributor')" />
    <x-backpack::menu-dropdown-item title="Vendedores" icon="la la-user-tie" :link="backpack_url('seller')" />
</x-backpack::menu-dropdown>

{{-- Comunicação --}}
<x-backpack::menu-item title="Mensagens de Contato" icon="la la-envelope" :link="backpack_url('contact-message')" />
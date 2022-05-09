<script>
    import {
        Input,
        Navbar,
        NavbarBrand, NavItem, NavLink,
    } from 'sveltestrap';

    import {AuthService} from '../services/AuthService';
    import {navigate} from "svelte-navigator";

    async function handleLogout() {
        await AuthService.logout();
        await navigate('/login');
    }
</script>

<Navbar color="light" light expand="md">
    <NavbarBrand href="/">Notizen</NavbarBrand>
    <Input
            type="search"
            name="search"
            id="searchNotes"
            placeholder="Suchen"
    />
    <a class="nav-link" on:click|preventDefault={handleLogout} href="/"> Logout</a>

</Navbar>
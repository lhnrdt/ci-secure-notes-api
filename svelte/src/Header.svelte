<script>

import {Link} from "svelte-navigator";
import {Navbar, NavbarBrand, NavbarToggler} from "sveltestrap";

let isOpen = false;

function handleUpdate(e) {
    isOpen = e.detail.isOpen;
}

</script>

<Navbar color="light">
    <NavbarBrand href="/">Notizen</NavbarBrand>
    <NavbarToggler on:click={() => (isOpen = !isOpen)}/>
</Navbar>

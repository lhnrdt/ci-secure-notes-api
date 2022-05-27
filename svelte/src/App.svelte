<script>

    import Home from "./routes/Home.svelte";
    import {Router, Route} from "svelte-navigator";
    import Navbar from "./components/Navbar.svelte";
    import Register from "./routes/Register.svelte";
    import Login from "./routes/Login.svelte";
    import {BootstrapToast, ToastContainer} from "svelte-toasts";

</script>

<Router>
    <Route path="/">
        <Navbar loggedIn={true} />
        <Home/>
    </Route>
    <Route path="/register">
        <Navbar loggedIn={false} />
        <Register/>
    </Route>
    <Route path="/login">
        <Navbar loggedIn={false} />
        <Login/>
    </Route>
</Router>
<ToastContainer placement="bottom-right" let:data={data}>
    <BootstrapToast {data}/>
</ToastContainer>


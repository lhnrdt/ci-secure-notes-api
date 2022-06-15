<script>

    import Home from "./routes/Home.svelte";
    import {Router, Route, useLocation} from "svelte-navigator";
    import Register from "./routes/Register.svelte";
    import Login from "./routes/Login.svelte";
    import {BootstrapToast, ToastContainer} from "svelte-toasts";
</script>

<Router>
    <Route path="/">
        <Home/>
    </Route>
    <Route path="/register">
        <Register/>
    </Route>
    <Route path="/login">
        <Login/>
    </Route>
</Router>
<ToastContainer placement="bottom-left" let:data={data}>
    <BootstrapToast {data}/>
</ToastContainer>


<script>

    import Home from "./routes/Home.svelte";
    import {Router, Link, Route} from "svelte-navigator";
    import Header from "./components/Navbar.svelte";
    import Register from "./routes/Register.svelte";
    import Toast from "./components/Toast.svelte";
    import Login from "./routes/Login.svelte";

</script>

<main>
    <Router>

        <Route path="/">
            <Header></Header>
            <Home/>
        </Route>
        <Route path="/register">
            <Register/>
        </Route>
        <Route path="/login">
            <Login/>
        </Route>
    </Router>
    <Toast/>
</main>


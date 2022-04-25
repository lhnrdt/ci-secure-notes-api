<script>

    import Home from "./routes/Home.svelte";
    import {Router, Link, Route} from "svelte-navigator";
    import Header from "./Header.svelte";
    import Register from "./routes/Register.svelte";

</script>

<main>
    <Router>
        <Header></Header>

        <Route path="/">
            <Home/>
        </Route>
        <Route path="/register">
            <Register/>
        </Route>

    </Router>
</main>


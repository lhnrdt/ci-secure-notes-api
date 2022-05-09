<script>

    import {onMount} from "svelte";
    import {navigate} from "svelte-navigator";
    import {Col, Container, Row} from "sveltestrap";
    import Sidebar from "../components/Sidebar.svelte";
    import {DataService} from "../services/DataService";
    import {AuthService} from "../services/AuthService";
    import {toasts} from "../stores/notficationStores";
    import Note from "../components/Note.svelte";

    let notes = [];

    onMount(async () => {
        try {
            const notesRes = await DataService.getUserNotes();
            notes = await notesRes['note'];
        } catch (e) {
            toasts.send('Error', 'Login expired.', 3000);
            await AuthService.logout();
            await navigate('/');
        }
    })

</script>

<h1>Notes</h1>

<Container fluid>
    <Row>
        <Col md="10">
            <Container fluid class="notes">
                <Row>
                    {#each notes as note}
                        <Col>
                            <Note note={note}/>
                        </Col>

                    {:else}
                        <p>loading...</p>
                    {/each}
                </Row>
            </Container>
        </Col>
    </Row>
</Container>
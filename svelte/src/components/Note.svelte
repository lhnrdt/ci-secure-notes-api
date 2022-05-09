<script>
    import {
        Card,
        CardBody,
        CardHeader,
        CardSubtitle,
        CardText,
        CardTitle
    } from "sveltestrap";

    export let note;

</script>

<Card class="note mb-3">
    <CardHeader>
        <CardTitle>{note.title}</CardTitle>
        <CardSubtitle>{note.name}</CardSubtitle>
    </CardHeader>
    <CardBody>
        <CardText>{note.content}</CardText>
    </CardBody>
</Card>
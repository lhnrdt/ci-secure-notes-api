<script>
    import {Utils} from "../services/Utils";
    import {DataService} from "../services/DataService";
    import {categoryStore} from "../stores";


    let modal;
    let categoryData;
    let selectedColor;

    let selectableColors = ["#fbb4ae", "#b3cde3", "#ccebc5", "#decbe4", "#fed9a6", "#ffffcc", "#e5d8bd", "#fddaec", "#f2f2f2"];

    export const show = (note) => {
        jQuery(modal).modal('show');
        categoryData = note;
    }

    export const hide = () => {
        jQuery(modal).modal('hide');
    }

    const handleSubmit = async (e) => {

        const formData = new FormData(e.target);
        const user = Utils.getUser();

        formData.append('user_id', user.id);

        let res = await DataService.postResource('/api/categories', formData);

        $categoryStore = [...$categoryStore, res.category];

        hide();
    }
</script>

<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" bind:this={modal}>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Neue Kategorie</h5>
                <button on:click={hide} type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="newNote" on:submit|preventDefault={handleSubmit}>
                <div class="modal-body">
                    <div class="card-title">
                        <input type="text" class="form-control fw-bolder" placeholder="Name" name="name" value="">
                    </div>

                    <p class="card-text"></p>

                    <div class="input-group mb-3">
                        <label class="input-group-text" for="selectColor">Farbe</label>
                        <select id="selectColor" name="color" class="form-select" aria-label="Color"
                                bind:value={selectedColor}
                                style:background-color={selectedColor}
                        >
                            {#each selectableColors as color}
                                <option value={color} style:background-color={color}>
                                    {color}
                                </option>
                            {/each}
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Speichern
                    </button>
                    <button on:click={hide} type="button" class="btn btn-secondary" data-dismiss="modal">
                        Abbrechen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
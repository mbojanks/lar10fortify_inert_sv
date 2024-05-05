<script lang="ts">
    import { FormField } from "yesvelte";
    import type {FormImgSelectProps} from '$lib/../types/components/ImgSelect.types';

    type $$Props = FormImgSelectProps;

    let props: $$Props = {};
	let imgSelectProps: $$Props = {};
    let id: string;

	$: props = {
		label,
		hint,
		state,
		componentName,
		id,
	}

	$: imgSelectProps = {
		name,
        delfileext,
        noimgurl,
        fileinputext,
        accept,
        multiple,
        size
	}
    export let componentName: $$Props['componentName'] = 'form-img-select';
    export let label: $$Props['label'] = '';
    export let name: $$Props['name'] = undefined;
    export let value: $$Props['value'] /* string | null | FileList | undefined */ = undefined;
    export let delfileext: $$Props['delfileext'] = '_filedelete';
    export let noimgurl: $$Props['noimgurl'] = '/storage/public/users/nouserimg.png';
    export let fileinputext: $$Props['fileinputext'] = '_file';
    export let accept: $$Props['accept'] = "image/*";
    export let multiple : $$Props['multiple'] = false;
    export let size: $$Props['size'] = undefined;
    export let state: $$Props['state'] = undefined;
    export let hint: $$Props['hint'] = undefined;
    import ImgSelect from "./ImgSelect.svelte";
</script>
<FormField {...props} {...$$restProps}>
    <slot slot="label" name="label" />

    <svelte:fragment slot="group">
        <ImgSelect {...imgSelectProps} bind:value />
    </svelte:fragment>
</FormField>

<script lang="ts">
    import { Avatar, El } from "yesvelte";
    import type { ImgSelectProps } from "resources/ts/types/components/ImgSelect.types";

    type $$Props = ImgSelectProps;

    export let componentName: $$Props['componentName'] = 'img-select';
    export let name: $$Props['name'] = undefined;
    export let value: $$Props['value'] /* string | null | FileList | undefined */ = undefined;
    export let delfileext: $$Props['delfileext'] = '_filedelete';
    export let noimgurl: $$Props['noimgurl'] = '/storage/public/users/nouserimg.png';
    export let fileinputext: $$Props['fileinputext'] = '_file';
    export let accept: $$Props['accept'] = "image/*";
    export let multiple : $$Props['multiple'] = false;
    export let size: $$Props['size'] = undefined;
    export let id: $$Props['id'] = name??'' + fileinputext;

    let props: $$Props = {
        componentName,
        delfileext,
        noimgurl,
        fileinputext,
        accept,
        multiple,
        size
    };

    $: props = {
        componentName,
        delfileext,
        noimgurl,
        fileinputext,
        accept,
        multiple,
        size
    };

    let origVal: $$Props['value'] /* string | null | FileList | undefined*/ = value;
    let fi: HTMLInputElement;
    let dfel: HTMLInputElement;
    let files: FileList | undefined = undefined;
    let srcValue: string | undefined;
    $: getBase64(files?files[0]:undefined);
    $: value = (files?.length && files instanceof FileList) ? files : (value === null ? value : origVal);
    $: srcValue = (value === files ? avatar?.toString() : (value ? value.toString() : noimgurl));
    $: if(dfel) dfel.checked = !value;
    import i18n from "$lib/i18n";
    import { onMount } from "svelte";
    let avatar: string | ArrayBuffer | null | undefined;
    function getBase64(image: File | undefined) {
        if (image) {
            const reader = new FileReader();
            reader.readAsDataURL(image);
            reader.onload = e => {
                avatar = e.target?.result;
            };
        } else {
            avatar = null;
        }
    };
    const onClick = () => {
        if (value !== null) {
            value = null;
            fi.files = null;
            files = undefined;
        } else {
            value = (files?.length && files instanceof FileList ? files : origVal);
            dfel.checked = !origVal;
        }
    };
    const onClkF = () => {
        if (fi) {
            fi.click();
        }
    };
    const onChngF = () => {
        files = fi.files ?? undefined;
        if (files) dfel.checked = false;
    };
    const onAvBlur = () => {
        if (!files) {value=origVal};
    };
    onMount(() => {
        origVal = value;
        dfel.checked = !origVal;
    })
</script>
<El bind:id bind:value>
    <Avatar on:blur={onAvBlur} title={$i18n.t('server:imgselect.title')} {size}>
        <!-- svelte-ignore a11y-click-events-have-key-events -->
        <!-- svelte-ignore a11y-no-noninteractive-element-interactions -->
        <img src={ srcValue } alt="value" on:click={onClkF} />
    </Avatar>
    <input type="checkbox" id={name??'' + delfileext} name={name??'' + delfileext} on:change={onClick} bind:this={dfel} />
    <label for={name??'' + delfileext}>{$i18n.t('server:imgselect.delimg')}</label>
    <!--<Checkbox inline id={name + delfileext} name={name + delfileext} on:change={onClick}>{$i18n.t('server:imgselect.delimg')}</Checkbox> -->
    <input {name} type="file" {accept} {multiple} bind:this={fi} style="display:none;" on:change={onChngF} />
</El>

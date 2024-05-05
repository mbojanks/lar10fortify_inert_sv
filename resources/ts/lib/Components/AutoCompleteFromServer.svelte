<script lang="ts">
    import { FormAutocomplete } from "yesvelte";
    import {onMount} from 'svelte';
    import i18n from "$lib/i18n";
    let items: any[] = [];
    let ctrlValue : any = undefined;
    let valueItem: any = undefined;
    let text: string = '';
    export let optUrl: string;
    export let idUrl: string;
    export let label: string;
    export let name: string;
    export let hint: string|undefined;
    export let state: 'valid' | 'invalid' | undefined;
    export let itemToValue: ((it: any) => any) = (it: any) => {if(it) return it; else return undefined;};
    export let valueToItem = (id: number | string | undefined) => {
        //console.log('in ValueToItem');
        if (!id) return [];
        if (!idUrl) return [];
        let url = `${idUrl}/${id}`;
        window.fetch(url).then((response) => response.json()).then((json) => {
                items = [json] ?? [];
            }).catch(() => {
                items = [];
            }).finally(() => {
                ctrlValue = items[0] ?? undefined;
                valueItem = ctrlValue;
                //console.log('ctrlValue', ctrlValue)
            });
    };
    export let value: number | string | undefined = undefined;
    let inputEl: HTMLInputElement | null;
    let blockBlur: boolean = false;
    $: if(ctrlValue === undefined) onBlur();
    onMount(() => {
        valueToItem(value);
    });
    function debounced(callback : CallableFunction, timeout = 300) {
        let timer : string | number | NodeJS.Timeout | undefined;
        return (...args : CustomEvent[]) => {
            clearTimeout(timer);
            timer = setTimeout(() => callback(...args), timeout);
        };
    }
    function getInputEl(qs: string, asValue: boolean = false) {
        if (!inputEl) {
            if (!asValue) {
                inputEl = document.querySelector(qs);
            } /*else {
                const segm = document.evaluate(`//div[contains(text(), '${qs}')]`, document, null, XPathResult.ANY_TYPE, null ).iterateNext();
                if(segm) {
                    inputEl = segm.parentElement as HTMLInputElement;
                }
            }*/
            if (inputEl) inputEl.placeholder = $i18n.t('server:acfs.searchhere');
            inputEl?.addEventListener('blur', () => {onBlur();});
            inputEl?.nextElementSibling?.addEventListener('mouseover',() => {blockBlur = true;});
            inputEl?.nextElementSibling?.addEventListener('mouseleave',() => {blockBlur = false;});
        }
    }
    function onInput(query: string) {
        text = query;
        getInputEl("input:focus-visible");
        if(inputEl) inputEl.value = query;
        if (query.length > 1) {
            var url = `${optUrl}?q=${encodeURIComponent(query)}`;
            window.fetch(url).then((response) => response.json()).then((json) => {
                items = json ?? [];
            }).catch(() => {
                items = [];
            });
            // NOTHING EXECuted here?!
        } else {
            items = [];
            if (query.length === 0) {
                onBlur();
            }
        }
    }
    function onChange(ev: CustomEvent) {
        valueItem = ev.detail;
        value = itemToValue(valueItem);
    }
    function onBlur() { // not real Blur - because not forwarded from the FormAutocomplete component!!
        if (!blockBlur) {
            items = [valueItem];
            ctrlValue = valueItem;
            if(inputEl) inputEl.value = '';
        }
    }
    const debouncedOnInput = debounced(onInput);
</script>
<FormAutocomplete
    {label}
    {name}
    {items}
    bind:value={ctrlValue}
    {state}
    {hint}
    placeholder={$i18n.t('server:acfs.searchhere')}
    on:input={(event) => debouncedOnInput(event.detail)}
    on:changed={onChange}
    let:item >
    <slot {item}/>
</FormAutocomplete>


<script lang="ts">
    import { Card, CardBody, FormInput, CardFooter, CardActions, Button, FormFileUpload, FormAutocomplete, Fieldset } from "yesvelte";
    import { router } from '@inertiajs/svelte';
    import type {RegisterErrors, Place, RegisterValues} from "$lib/../types";
    import i18n from "$lib/i18n";
    import AutoCompleteFromServer from "$lib/Components/AutoCompleteFromServer.svelte";


    /*let items: Place[] = [];
    let placeValue: Place | undefined = undefined;
    function debounced(callback : CallableFunction, timeout = 300) {
        let timer : string | number | NodeJS.Timeout | undefined;
        return (...args : CustomEvent[]) => {
            clearTimeout(timer);
            timer = setTimeout(() => callback(...args), timeout);
        };
    }
    function onInput(query: string) {
        if (query.length > 1) {
            var url = `${optUrl}?q=${encodeURIComponent(query)}`;
            window.fetch(url).then((response) => response.json()).then((json) => {
                items = json ?? [];
            }).catch(() => {
                items = [];
            });
        }
    }
    const debouncedOnInput = debounced(onInput);*/


    const onSubmit = () => {
        /*if (placeValue?.placeid) {
            values.user_place_id = placeValue.placeid;
        }*/
        //values.userimg = values.userimg ? values.userimg?.[0] : undefined;
        router.post(`/${locale}/register`, values, {
            forceFormData: true, // no need for this from Inertiajs 0.8
        });
    };
    let form: HTMLFormElement | undefined;
    const onReset = () => {
        form?.reset();
    }

    export let errors: RegisterErrors = {};
    export let optUrl: string = '';
    export let idUrl: string = '';
    export let locale: string = '';
    let values: RegisterValues = {
        email: "",
        password: "",
        password_confirmation: "",
        name: ""
    };
    </script>
    <Card title={$i18n.t('server:auth.registerForm')} mb="3">
        <form method="POST" action="#" bind:this={form} on:submit|preventDefault={onSubmit}>
            <CardBody>
                <Fieldset row>
                    <FormInput label={$i18n.t('server:auth.email')} type="email" name="email" required bind:value={values.email} state={errors.email ? 'invalid' : 'valid'} hint={errors.email}></FormInput>
                    <FormInput label={$i18n.t('server:auth.passwordonform')} name="password" type="password" required bind:value={values.password} state={errors.password ? 'invalid' : 'valid'} hint={errors.password}></FormInput>
                    <FormInput label={$i18n.t('server:auth.passwordconfirm')} name="password_confirmation" required type="password" bind:value={values.password_confirmation} state={errors.password ? 'invalid' : 'valid'} hint={errors.password}></FormInput>
                    <FormInput label={$i18n.t('server:auth.name')} name="name" required bind:value={values.name} state={errors.name ? 'invalid' : 'valid'} hint={errors.name}></FormInput>
                    <FormInput label={$i18n.t('server:auth.phone')} name="phone" bind:value={values.phone} state={errors.phone ? 'invalid' : 'valid'} hint={errors.phone}></FormInput>
                    <FormInput label={$i18n.t('server:auth.address')} type="text" name="useraddress" bind:value={values.useraddress} state={errors.useraddress ? 'invalid' : 'valid'} hint={errors.useraddress}></FormInput>
                    <!-- <FormAutocomplete label={$i18n.t('server:auth.place')} name="user_place_id" items={items} bind:value={placeValue} state={errors.user_place_id ? 'invalid' : 'valid'} hint={errors.user_place_id} on:input={(event) => debouncedOnInput(event.detail)} let:item >
                        {item.place} ({item.municipality})
                    </FormAutocomplete> -->
                    <AutoCompleteFromServer
                        {optUrl}
                        {idUrl}
                        label={$i18n.t('server:auth.place')}
                        name="user_place_id"
                        state={errors.user_place_id ? 'invalid' : 'valid'}
                        hint={errors.user_place_id}
                        bind:value={values.user_place_id}
                        itemToValue={((item) => item.placeid )}
                        let:item
                    >
                        {item.place} ({item.municipality})
                    </AutoCompleteFromServer>
                    <FormFileUpload label={$i18n.t('server:auth.userimage')} name="userimg" bind:files={values.userimg} state={errors.userimg ? 'invalid' : 'valid'} hint={errors.userimg}></FormFileUpload>
                </Fieldset>
            </CardBody>
            <CardFooter>
                <CardActions d="flex" gap="2">
                    <Button type="reset" on:click={onReset}>{$i18n.t('server:auth.reset')}</Button>
                    <Button color="primary" type="submit">{$i18n.t('server:auth.submit')}</Button>
                </CardActions>
            </CardFooter>
        </form>
    </Card>

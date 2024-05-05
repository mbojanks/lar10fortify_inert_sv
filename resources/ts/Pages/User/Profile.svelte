<script lang="ts">
    import { Card, CardBody, FormInput, CardFooter, CardActions, Button, Fieldset } from "yesvelte";
    import FormImgSelect from '$lib/Components/FormImgSelect.svelte';
    import { router } from '@inertiajs/svelte';
    import type {RegisterErrors, User, UserProfile} from "$lib/../types";
    import i18n from "$lib/i18n";
    import AutoCompleteFromServer from "$lib/Components/AutoCompleteFromServer.svelte";
    import Recordset from "$lib/Components/Recordset.svelte";

    const onSubmit = () => {
        //console.log('values', values);
        router.post(`/${locale}/user/profile-information`, {...values, "_method":"PUT"}, {
            forceFormData: true, // no need for this from Inertiajs 0.8
        });
    };
    let form: HTMLFormElement | undefined;
    const onReset = () => {
        form?.reset();
    }

    export let errors: RegisterErrors = {};
    export let user: User;
    export let optUrl: string = '';
    export let idUrl: string = '';
    export let locale: string = '';
    let values: UserProfile = {
        email: user.email,
        name: user.name,
        userimg: user.userimg,
        user_place_id: user.user_place_id,
        useraddress: user.useraddress,
        phone: user.phone
    };
</script>
<svelte:head>
    <title>{$i18n.t('server:auth.profileForm')}</title>
</svelte:head>
<Card title={$i18n.t('server:auth.profileForm')} mb="3">
    <form method="POST" action="#" bind:this={form} on:submit|preventDefault={onSubmit}>
        <CardBody>
            <Fieldset>
                <FormInput label={$i18n.t('server:auth.email')} type="email" name="email" required bind:value={values.email} state={errors.email ? 'invalid' : 'valid'} hint={errors.email}></FormInput>
                <FormInput label={$i18n.t('server:auth.name')} name="name" required bind:value={values.name} state={errors.name ? 'invalid' : 'valid'} hint={errors.name}></FormInput>
                <FormInput label={$i18n.t('server:auth.phone')} name="phone" bind:value={values.phone} state={errors.phone ? 'invalid' : 'valid'} hint={errors.phone}></FormInput>
                <FormInput label={$i18n.t('server:auth.address')} type="text" name="useraddress" bind:value={values.useraddress} state={errors.useraddress ? 'invalid' : 'valid'} hint={errors.useraddress}></FormInput>
                <AutoCompleteFromServer
                    {optUrl}
                    {idUrl}
                    label={$i18n.t('server:auth.place')}
                    name="user_place_id"
                    state={errors.user_place_id ? 'invalid' : 'valid'}
                    hint={errors.user_place_id}
                    bind:value={values.user_place_id}
                    itemToValue={(item) => item ? item.placeid : undefined}
                    let:item
                >
                    {item.place} ({item.municipality})
                </AutoCompleteFromServer>
                <FormImgSelect label={$i18n.t('server:auth.userimage')} name="userimg" bind:value={values.userimg} size="xl" state={errors.userimg ? 'invalid' : 'valid'} hint={errors.name}></FormImgSelect>
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

<script lang="ts">
    import { Button, Card, CardBody, CardHeader, El, FormInput, TabContent, TabItem, TabList, TabPanel, Tabs } from "yesvelte";
    import i18n from '$lib/i18n';
    import Profile from './Profile.svelte';
    import SingleForm from '$lib/Components/SingleForm.svelte';
    import TwoFa from '$lib/Components/2fa.svelte';
    import type { ConfirmPasswordErrors, RegisterErrors, UpdatePasswordErrors, User } from "resources/ts/types";
    import ConfirmPasswordC from "$lib/Components/ConfirmPasswordC.svelte";
    import { router } from "@inertiajs/svelte";
    import DeleteSelf from "$lib/Components/DeleteSelf.svelte";

    export let errors: {updatePassword?: UpdatePasswordErrors, updateProfileInformation?: RegisterErrors, twoFactorAuthentication?: any, deleteUser?: ConfirmPasswordErrors } = {updateProfileInformation: {}};
    let updPwdErrors: any;
    let updateProfileInformationErrors: RegisterErrors;
    $: if (typeof errors.updatePassword !== 'undefined') updPwdErrors = errors.updatePassword; else updPwdErrors={current_password:''};
    $: if (typeof errors.updateProfileInformation !== 'undefined') updateProfileInformationErrors = errors.updateProfileInformation; else updateProfileInformationErrors={email:''};
    let updateValues: any = {current_password: ''};
    $: valuesToUpdate = updateValues;
    export let user: User;
    export let locale: string = '';
    $: loc = (locale ? "/" + locale : "");

    export let optUrl: string = '';
    export let idUrl: string = '';

</script>
<svelte:head>
    <title>{$i18n.t('server:auth.profileForm')}</title>
</svelte:head>
<El>
    <Card>
        <Tabs>
            <CardHeader>
                <!--<h2 style="width: 180px;">User Profile</h2> -->
                <TabList>
                    <TabItem active>{$i18n.t('server:auth.userprofile')}</TabItem>
                    <TabItem>{$i18n.t('server:auth.updatepassword')}</TabItem>
                    <TabItem>{$i18n.t('server:auth.2fa')}</TabItem>
                    <TabItem>{$i18n.t('server:auth.accountdelete')}</TabItem>
                </TabList>
            </CardHeader>
            <CardBody>
                <TabContent>
                    <TabPanel>
                        <Profile {user} errors={updateProfileInformationErrors} {optUrl} {idUrl} {locale}></Profile>
                    </TabPanel>
                    <TabPanel>
                        <SingleForm submitUrl={`${loc}/user/password`} title={$i18n.t('server:auth.updatepassword')} description="" bind:values={valuesToUpdate}>
                            <FormInput colMd="auto" label={$i18n.t('server:auth.passwordold')} type="password" name="current_password" bind:value={valuesToUpdate.current_password} state={(updPwdErrors.current_password) ? 'invalid' : 'valid'} hint={errors.updatePassword?.current_password}></FormInput>
                            <FormInput colMd="auto" label={$i18n.t('server:auth.passwordnew')} type="password" name="password" bind:value={valuesToUpdate.password} state={(updPwdErrors.password) ? 'invalid' : 'valid'} hint={errors.updatePassword?.password}></FormInput>
                            <FormInput colMd="auto" label={$i18n.t('server:auth.passwordnewconfirm')} type="password" name="password_confirmation" bind:value={valuesToUpdate.password_confirmation} state={(updPwdErrors.password_confirmation) ? 'invalid' : 'valid'} hint={errors.updatePassword?.password_confirmation}></FormInput>
                        </SingleForm>
                    </TabPanel>
                    <TabPanel>
                        <TwoFa title={$i18n.t('server:auth.2fa')} description={$i18n.t('server:auth.2fadesc')} {locale} />
                    </TabPanel>
                    <TabPanel>
                        <DeleteSelf errors={errors.deleteUser} {locale}></DeleteSelf>
                    </TabPanel>
                </TabContent>
            </CardBody>
        </Tabs>
    </Card>
</El>

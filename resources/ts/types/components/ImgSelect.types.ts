import type { ElProps } from "yesvelte";
import type { AvatarSizes } from "yesvelte/avatar/Avatar.types";
import type { FormFieldProps } from "yesvelte/form/Form.types";

export interface ImgSelectProps extends Partial<ElProps> {
    name?: string | undefined;
    value?: unknown; //string | null | FileList | undefined;
    delfileext?: string;
    noimgurl?: string;
    fileinputext?: string;
    accept?: string;
    multiple?: boolean;
    size?: AvatarSizes | undefined;
};

export interface FormImgSelectProps extends Partial<FormFieldProps>, Partial<ImgSelectProps> { }

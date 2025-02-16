import ModalFactory from 'core/modal_factory';
import {get_string as getString} from 'core/str';

export const init = async () => {
    const modal = await ModalFactory.create({
        type: ModalFactory.types.CANCEL,
        title: getString('errormodal:title', 'local_htmx'),
        body: getString('errormodal:body', 'local_htmx'),
    });

    htmx.on("htmx:responseError", function (evt) {
        modal.show();
        console.error("Error response", evt);
    });
};

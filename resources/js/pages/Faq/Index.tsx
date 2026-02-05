import AppHeaderLayout from '@/layouts/app/app-header-layout';
import { FaqEntryResource, SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/react';

// Note: the top bar in the layout does not work for guests yet, this has been fixed in pr 132
// To prevent errors, login first before accessing this site for now
export default function Index() {
    const { props } = usePage<SharedData>();

    const faq = props.faq as FaqEntryResource[];
    return (
        <AppHeaderLayout>
            <Head title={'FAQ'} />
            {faq.map((faq) => (
                <div key={'faq-' + faq.id} className={'mt-4'}>
                    <p className={'text-lg'}>{faq.title}</p>
                    <p>{faq.body}</p>
                </div>
            ))}
        </AppHeaderLayout>
    );
}

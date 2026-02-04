import { Head, usePage } from '@inertiajs/react';
import AppHeaderLayout from '@/layouts/app/app-header-layout';
import { FaqEntryResource, SharedData } from '@/types';

// Note: the top bar in the layout does not work for guests yet, this has been fixed in pr 132
// To prevent errors, login first before accessing this site for now
export default function Index() {
    const { props } = usePage<SharedData>();

    // @ts-expect-error Types will be fixed later, data wrapper will be removed in pr 132
    const faq = props.faq.data as FaqEntryResource[];

    console.log(faq);
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

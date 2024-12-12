import PrimaryButton from '@/Components/PrimaryButton';
import { Head } from '@inertiajs/react';
// import { PageProps } from '../../../../../resources/js/types/index';
import { Button } from '@/components/ui/button';
import { PageProps } from '@/types/index';

export default function Baz({
  auth,
  laravelVersion,
  phpVersion,
}: PageProps<{ laravelVersion: string; phpVersion: string }>) {
  return (
    <>
      <Head title="Baz page" />
      <ul className="flex items-center">
        <li className="text-3xl text-blue-700">
          <strong>PHP version:</strong> {phpVersion}
        </li>
        <li>
          <strong className="text-3xl text-green-700">Laravel version:</strong>{' '}
          {laravelVersion}
        </li>
      </ul>
      <p>
        {auth.user ? (
          <>
            You are <strong>logged</strong> in!
          </>
        ) : (
          <>
            You are <strong>not</strong> logged in!
          </>
        )}
        <div className="flex gap-2">
          <PrimaryButton>Save</PrimaryButton>
          <Button>Click me</Button>
        </div>
      </p>
    </>
  );
}

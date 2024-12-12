import { PageProps, Post } from '@/types';
import { Head, router } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Edit, MessageCircle, Star, Trash2 } from 'lucide-react';

interface PostShowProps extends PageProps {
  post: Post & {
    user: {
      name: string;
    };
    comments: Array<{
      id: number;
      content: string;
      user: {
        name: string;
      };
      created_at: string;
    }>;
    average_rating?: number;
  };
}

export default function Show({ post, auth }: PostShowProps) {
  const handleDelete = () => {
    if (confirm('Are you sure you want to delete this post?')) {
      router.delete(route('post.post.destroy', post.id));
    }
  };

  return (
    <div className="container mx-auto px-4 py-8">
      <Head title={post.title} />

      <Card className="mx-auto max-w-3xl">
        <CardHeader className="flex flex-row items-center justify-between">
          <CardTitle>{post.title}</CardTitle>
          {/* {auth?.user.id === post.user_id && ( */}
          <div className="flex space-x-2">
            <Button
              variant="outline"
              size="icon"
              onClick={() => router.get(route('post.post.edit', post.id))}
            >
              <Edit className="h-4 w-4" />
            </Button>
            <Button variant="destructive" size="icon" onClick={handleDelete}>
              <Trash2 className="h-4 w-4" />
            </Button>
          </div>
          {/* )} */}
        </CardHeader>

        <CardContent>
          <div className="mb-4 flex items-center space-x-4">
            <div className="flex items-center text-yellow-500">
              <Star className="mr-1 h-4 w-4" />
              <span>
                {post.average_rating
                  ? post.average_rating.toFixed(1)
                  : 'No ratings'}
              </span>
            </div>
            <div className="text-muted-foreground flex items-center">
              <MessageCircle className="mr-1 h-4 w-4" />
              <span>{post.comments?.length || 0} Comments</span>
            </div>
          </div>

          <div className="prose max-w-none">{post.content}</div>

          {/* Media Display */}
          {post.media && post.media.length > 0 && (
            <div className="mt-4 grid grid-cols-3 gap-4">
              {post.media.map((mediaItem, index) => (
                <img
                  key={index}
                  src={mediaItem}
                  alt={`Media ${index + 1}`}
                  className="rounded-lg object-cover"
                />
              ))}
            </div>
          )}

          {/* Comments Section */}
          <div className="mt-8">
            <h3 className="mb-4 text-lg font-semibold">Comments</h3>
            {post.comments?.map((comment) => (
              <div key={comment.id} className="border-b py-2 last:border-b-0">
                <div className="mb-2 flex items-center justify-between">
                  <span className="font-medium">{comment.user.name}</span>
                  <span className="text-muted-foreground text-sm">
                    {new Date(comment.created_at).toLocaleString()}
                  </span>
                </div>
                <p>{comment.content}</p>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>
    </div>
  );
}

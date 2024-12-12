import { PageProps, Post } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import React from 'react';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';

interface PostEditProps extends PageProps {
  post: Post;
}

export default function Edit({ post }: PostEditProps) {
  const { data, setData, put, processing, errors } = useForm({
    title: post.title,
    content: post.content,
    media: [],
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    put(route('post.post.update', post.id), {
      preserveScroll: true,
    });
  };

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const files = e.target.files;
    if (files) {
      setData('media', Array.from(files));
    }
  };

  return (
    <div className="container mx-auto px-4 py-8">
      <Head title={`Edit Post: ${post.title}`} />

      <Card className="mx-auto max-w-2xl">
        <CardHeader>
          <CardTitle>Edit Post</CardTitle>
        </CardHeader>

        <CardContent>
          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <Label htmlFor="title">Title</Label>
              <Input
                id="title"
                type="text"
                value={data.title}
                onChange={(e) => setData('title', e.target.value)}
                placeholder="Enter post title"
                className="mt-1"
              />
              {errors.title && (
                <p className="text-destructive mt-1 text-sm">{errors.title}</p>
              )}
            </div>

            <div>
              <Label htmlFor="content">Content</Label>
              <Textarea
                id="content"
                value={data.content}
                onChange={(e) => setData('content', e.target.value)}
                placeholder="Write your post content"
                className="mt-1 min-h-[200px]"
              />
              {errors.content && (
                <p className="text-destructive mt-1 text-sm">
                  {errors.content}
                </p>
              )}
            </div>

            <div>
              <Label htmlFor="media">Additional Media</Label>
              <Input
                id="media"
                type="file"
                multiple
                onChange={handleFileChange}
                className="mt-1"
              />
            </div>

            {/* Optional: Display existing media */}
            {post.media && post.media.length > 0 && (
              <div>
                <Label>Existing Media</Label>
                <div className="mt-2 grid grid-cols-3 gap-4">
                  {post.media.map((mediaItem, index) => (
                    <img
                      key={index}
                      src={mediaItem}
                      alt={`Media ${index + 1}`}
                      className="rounded-lg object-cover"
                    />
                  ))}
                </div>
              </div>
            )}

            <Button type="submit" disabled={processing}>
              {processing ? 'Updating...' : 'Update Post'}
            </Button>
          </form>
        </CardContent>
      </Card>
    </div>
  );
}

// import { PageProps, Post } from '@/types';
// import { Head, router } from '@inertiajs/react';

// import { Button } from '@/components/ui/button';
// import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
// import {
//   Dialog,
//   DialogContent,
//   DialogDescription,
//   DialogFooter,
//   DialogHeader,
//   DialogTitle,
//   DialogTrigger,
// } from '@/components/ui/dialog';
// import { Edit, MessageCircle, Star, Trash2 } from 'lucide-react';

// interface PostShowProps extends PageProps {
//   post: Post & {
//     user: {
//       name: string;
//     };
//     comments: Array<{
//       id: number;
//       content: string;
//       user: {
//         name: string;
//       };
//       created_at: string;
//     }>;
//     average_rating?: number;
//   };
// }

// export default function Show({ post, auth }: PostShowProps) {
//   const handleDelete = () => {
//     router.delete(route('post.post.destroy', post.id));
//     // if (confirm('Are you sure you want to delete this post?')) {
//     // }
//   };

//   return (
//     <div className="container mx-auto px-4 py-8">
//       <Head title={post.title} />

//       <Card className="mx-auto max-w-3xl">
//         <CardHeader className="flex flex-row items-center justify-between">
//           <CardTitle>{post.title}</CardTitle>
//           {/* {auth?.user.id === post.user_id && ( */}
//           <div className="flex space-x-2">
//             <Button
//               variant="outline"
//               size="icon"
//               onClick={() => router.get(route('post.post.edit', post.id))}
//             >
//               <Edit className="h-4 w-4" />
//             </Button>

//             <Dialog>
//               <DialogTrigger asChild>
//                 <Button variant="destructive" size="icon">
//                   <Trash2 className="h-4 w-4" />
//                 </Button>
//               </DialogTrigger>
//               <DialogContent className="sm:max-w-[425px]">
//                 <DialogHeader>
//                   <DialogTitle>Delete Post</DialogTitle>
//                   <DialogDescription>
//                     Are you sure you want to delete this post?
//                   </DialogDescription>
//                 </DialogHeader>
//                 <div className=""></div>
//                 <DialogFooter>
//                   <Button
//                     variant="destructive"
//                     // size="icon"
//                     onClick={handleDelete}
//                   >
//                     Delete
//                   </Button>
//                 </DialogFooter>
//               </DialogContent>
//             </Dialog>
//           </div>
//           {/* )} */}
//         </CardHeader>

//         <CardContent>
//           <div className="mb-4 flex items-center space-x-4">
//             <div className="flex items-center text-yellow-500">
//               <Star className="mr-1 h-4 w-4" />
//               <span>
//                 {post.average_rating
//                   ? post.average_rating.toFixed(1)
//                   : 'No ratings'}
//               </span>
//             </div>
//             <div className="text-muted-foreground flex items-center">
//               <MessageCircle className="mr-1 h-4 w-4" />
//               <span>{post.comments?.length || 0} Comments</span>
//             </div>
//           </div>

//           <div className="prose max-w-none">{post.content}</div>

//           {/* Media Display */}
//           {post.media && post.media.length > 0 && (
//             <div className="mt-4 grid grid-cols-3 gap-4">
//               {post.media.map((mediaItem, index) => (
//                 <img
//                   key={index}
//                   src={mediaItem}
//                   alt={`Media ${index + 1}`}
//                   className="rounded-lg object-cover"
//                 />
//               ))}
//             </div>
//           )}

//           {/* Comments Section */}
//           <div className="mt-8">
//             <h3 className="mb-4 text-lg font-semibold">Comments</h3>
//             {post.comments?.map((comment) => (
//               <div key={comment.id} className="border-b py-2 last:border-b-0">
//                 <div className="mb-2 flex items-center justify-between">
//                   <span className="font-medium">{comment.user.name}</span>
//                   <span className="text-muted-foreground text-sm">
//                     {new Date(comment.created_at).toLocaleString()}
//                   </span>
//                 </div>
//                 <p>{comment.content}</p>
//               </div>
//             ))}
//           </div>
//         </CardContent>
//       </Card>
//     </div>
//   );
// }
import { PageProps, Post } from '@/types';
import { Head, router, useForm } from '@inertiajs/react';
import React, { useState } from 'react';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog';
import { Textarea } from '@/components/ui/textarea';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {
  Edit,
  MessageCircle,
  SendHorizontal,
  Star,
  Trash2,
  X,
} from 'lucide-react';

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
    current_user_rating?: number;
  };
}

export default function Show({ post, auth }: PostShowProps) {
  const [rating, setRating] = useState(post.current_user_rating || 0);

  const {
    data,
    setData,
    post: submitComment,
    processing,
    errors,
    reset,
  } = useForm({
    content: '',
    post_id: post.id,
  });

  const handleDelete = () => {
    router.delete(route('post.post.destroy', post.id));
  };

  const handleRating = (selectedRating: number) => {
    setRating(selectedRating);
    router.post(route('post.post.rate', post.id), { rating: selectedRating });
  };

  const handleCommentSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // alert(post.id);
    submitComment(route('comment.comment.store'), {
      onSuccess: () => reset(),
    });
  };

  const handleCommentDelete = (commentId: number) => {
    router.delete(route('comment.comment.destroy', commentId));
  };

  return (
    <AuthenticatedLayout>
      {/* <Head title="Post" /> */}

      <div className="container mx-auto px-4 py-8">
        <Head title={post.title} />

        <Card className="mx-auto max-w-3xl">
          <CardHeader className="flex flex-row items-center justify-between">
            <CardTitle>{post.title}</CardTitle>
            <div className="flex space-x-2">
              <Button
                variant="outline"
                size="icon"
                onClick={() => router.get(route('post.post.edit', post.id))}
              >
                <Edit className="h-4 w-4" />
              </Button>

              <Dialog>
                <DialogTrigger asChild>
                  <Button variant="destructive" size="icon">
                    <Trash2 className="h-4 w-4" />
                  </Button>
                </DialogTrigger>
                <DialogContent className="sm:max-w-[425px]">
                  <DialogHeader>
                    <DialogTitle>Delete Post</DialogTitle>
                    <DialogDescription>
                      Are you sure you want to delete this post?
                    </DialogDescription>
                  </DialogHeader>
                  <DialogFooter>
                    <Button variant="destructive" onClick={handleDelete}>
                      Delete
                    </Button>
                  </DialogFooter>
                </DialogContent>
              </Dialog>
            </div>
          </CardHeader>

          <CardContent>
            {/* Rating System */}
            <div className="mb-4 flex items-center space-x-4">
              <div className="flex items-center text-yellow-500">
                <div className="flex">
                  {[1, 2, 3, 4, 5].map((star) => (
                    <Star
                      key={star}
                      className={`h-6 w-6 cursor-pointer ${
                        star <= rating
                          ? 'fill-current text-yellow-500'
                          : 'text-gray-300'
                      }`}
                      onClick={() => handleRating(star)}
                    />
                  ))}
                </div>
                <span className="ml-2">
                  {post.average_rating
                    ? `${post.average_rating.toFixed(1)} / 5`
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

            {/* Comment Section */}
            <div className="mt-8">
              {/* Comment Input */}
              <form
                onSubmit={handleCommentSubmit}
                className="mb-4 flex space-x-2"
              >
                <Textarea
                  placeholder="Write a comment..."
                  value={data.content}
                  onChange={(e) => setData('content', e.target.value)}
                  className="flex-grow"
                />
                {/* <Input type="hidden" value={post.id} /> */}
                <Button type="submit" disabled={processing}>
                  <SendHorizontal className="h-4 w-4" />
                </Button>
              </form>

              {/* Comments List */}
              <h3 className="mb-4 text-lg font-semibold">Comments</h3>
              {post.comments?.map((comment) => (
                <div
                  key={comment.id}
                  className="group relative border-b py-2 last:border-b-0"
                >
                  <div className="mb-2 flex items-center justify-between">
                    <span className="font-medium">{comment.user.name}</span>
                    <span className="text-muted-foreground text-sm">
                      {new Date(comment.created_at).toLocaleString()}
                    </span>
                  </div>
                  <p>{comment.content}</p>
                  {/* {auth.user && ( */}
                  <Button
                    variant="ghost"
                    size="icon"
                    className="absolute right-0 top-0 opacity-0 group-hover:opacity-100"
                    onClick={() => handleCommentDelete(comment.id)}
                  >
                    <X className="h-4 w-4 text-red-500" />
                  </Button>
                  {/* )} */}
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>
    </AuthenticatedLayout>
  );
}

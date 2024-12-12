// import { PageProps, PaginatedResponse, Post } from '@/types';
// import { Head, Link, router, usePage } from '@inertiajs/react';
// import React, { useState } from 'react';

// import { Button } from '@/components/ui/button';
// import {
//   Card,
//   CardContent,
//   CardFooter,
//   CardHeader,
//   CardTitle,
// } from '@/components/ui/card';
// import { Input } from '@/components/ui/input';
// import {
//   Pagination,
//   PaginationContent,
//   PaginationItem,
//   PaginationLink as ShadcnPaginationLink,
// } from '@/components/ui/pagination';
// import { Star } from 'lucide-react';

// export default function PostIndex() {
//   const { posts } =
//     usePage<PageProps<{ posts: PaginatedResponse<Post> }>>().props;
//   const [search, setSearch] = useState('');

//   const handleSearch = (e: React.FormEvent) => {
//     e.preventDefault();
//     router.get(
//       '/posts',
//       { search },
//       {
//         preserveState: true,
//         replace: true,
//       },
//     );
//   };

//   return (
//     <div className="container mx-auto px-4 py-8">
//       <Head title="Posts" />

//       <div className="mb-8">
//         <form onSubmit={handleSearch} className="flex space-x-2">
//           <Input
//             type="search"
//             value={search}
//             onChange={(e) => setSearch(e.target.value)}
//             placeholder="Search posts..."
//             className="flex-grow"
//           />
//           <Button type="submit">Search</Button>
//         </form>
//       </div>

//       <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
//         {posts.data.map((post) => (
//           <Card key={post.id} className="transition-shadow hover:shadow-lg">
//             <CardHeader>
//               <CardTitle>
//                 <Link href={`/posts/${post.id}`} className="hover:text-primary">
//                   {post.title}
//                 </Link>
//               </CardTitle>
//             </CardHeader>

//             <CardContent>
//               <p className="text-muted-foreground line-clamp-3">
//                 {post.content.substring(0, 150)}...
//               </p>
//             </CardContent>

//             <CardFooter className="flex items-center justify-between">
//               <div className="flex items-center space-x-2">
//                 <div className="flex items-center text-yellow-500">
//                   <Star className="mr-1 h-4 w-4" />
//                   <span>
//                     {post.average_rating
//                       ? post.average_rating.toFixed(1)
//                       : 'N/A'}
//                   </span>
//                 </div>
//                 <div className="text-muted-foreground text-sm">
//                   Comments: {post.comments_count || 0}
//                 </div>
//               </div>
//             </CardFooter>
//           </Card>
//         ))}
//       </div>

//       {posts.data.length === 0 && (
//         <div className="text-muted-foreground py-8 text-center">
//           No posts found.
//         </div>
//       )}

//       {/* Pagination */}
//       {posts.last_page > 1 && (
//         <Pagination className="mt-auto pt-10">
//           <PaginationContent>
//             {posts.links.map((link, index) => (
//               <PaginationItem key={index}>
//                 <ShadcnPaginationLink
//                   href={link.url || '#'}
//                   isActive={link.active}
//                   onClick={(e) => {
//                     if (link.url) {
//                       e.preventDefault();
//                       router.get(link.url, {}, { preserveState: true });
//                     }
//                   }}
//                 >
//                   {link.label.replace('&laquo;', '←').replace('&raquo;', '→')}
//                 </ShadcnPaginationLink>
//               </PaginationItem>
//             ))}
//           </PaginationContent>
//         </Pagination>
//       )}
//     </div>
//   );
// }
import { PageProps, PaginatedResponse, Post } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/react';
import React, { useEffect, useState } from 'react';

import { Button } from '@/components/ui/button';
import {
  Card,
  CardContent,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import {
  Pagination,
  PaginationContent,
  PaginationItem,
  PaginationLink as ShadcnPaginationLink,
} from '@/components/ui/pagination';
import { Star, X } from 'lucide-react';

// Debounce utility function
function useDebounce<T>(value: T, delay: number): T {
  const [debouncedValue, setDebouncedValue] = useState<T>(value);

  useEffect(() => {
    const handler = setTimeout(() => {
      setDebouncedValue(value);
    }, delay);

    return () => {
      clearTimeout(handler);
    };
  }, [value, delay]);

  return debouncedValue;
}

export default function PostIndex() {
  const { posts } =
    usePage<PageProps<{ posts: PaginatedResponse<Post> }>>().props;

  const [search, setSearch] = useState('');
  const [isSearching, setIsSearching] = useState(false);
  const debouncedSearch = useDebounce(search, 500);

  // Use effect to trigger search when debounced value changes
  useEffect(() => {
    if (isSearching && debouncedSearch !== undefined) {
      router.get(
        '/posts',
        { search: debouncedSearch },
        {
          preserveState: true,
          replace: true,
        },
      );
    }
  }, [debouncedSearch, isSearching]);

  const handleSearchChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const value = e.target.value;
    setSearch(value);

    // Start searching only when user types something
    if (value.trim() !== '') {
      setIsSearching(true);
    } else {
      setIsSearching(false);
    }
  };

  // Optional: Reset search when clearing input
  const handleSearchClear = () => {
    setSearch('');
    setIsSearching(false);
    router.get('/posts', {}, { preserveState: true });
  };

  return (
    <div className="container mx-auto px-4 py-8">
      <Head title="Posts" />

      <div className="relative mb-8">
        <div className="flex space-x-2">
          <Input
            type="text"
            value={search}
            onChange={handleSearchChange}
            placeholder="Search posts..."
            className="flex-grow"
          />
          {search && (
            <Button
              variant="ghost"
              size="icon"
              onClick={handleSearchClear}
              className="absolute right-16 top-1/2 -translate-y-1/2 transform"
            >
              <X className="text-muted-foreground h-4 w-4" />
            </Button>
          )}
          <Button
            type="button"
            onClick={() => setIsSearching(true)}
            className="ml-4"
          >
            Search
          </Button>
        </div>
      </div>

      <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        {posts.data.map((post) => (
          <Card key={post.id} className="transition-shadow hover:shadow-lg">
            <CardHeader>
              <CardTitle>
                <Link href={`/posts/${post.id}`} className="hover:text-primary">
                  {post.title}
                </Link>
              </CardTitle>
            </CardHeader>

            <CardContent>
              <p className="text-muted-foreground line-clamp-3">
                {post.content.substring(0, 150)}...
              </p>
            </CardContent>

            <CardFooter className="flex items-center justify-between">
              <div className="flex items-center space-x-2">
                <div className="flex items-center text-yellow-500">
                  <Star className="mr-1 h-4 w-4" />
                  <span>
                    {post.average_rating
                      ? post.average_rating.toFixed(1)
                      : 'N/A'}
                  </span>
                </div>
                <div className="text-muted-foreground text-sm">
                  Comments: {post.comments_count || 0}
                </div>
              </div>
            </CardFooter>
          </Card>
        ))}
      </div>

      {posts.data.length === 0 && (
        <div className="text-muted-foreground py-8 text-center">
          No posts found.
        </div>
      )}

      {/* Pagination */}
      {posts.last_page > 1 && (
        <Pagination className="mt-auto pt-10">
          <PaginationContent>
            {posts.links.map((link, index) => (
              <PaginationItem key={index}>
                <ShadcnPaginationLink
                  href={link.url || '#'}
                  isActive={link.active}
                  onClick={(e) => {
                    if (link.url) {
                      e.preventDefault();
                      router.get(link.url, {}, { preserveState: true });
                    }
                  }}
                >
                  {link.label.replace('&laquo;', '←').replace('&raquo;', '→')}
                </ShadcnPaginationLink>
              </PaginationItem>
            ))}
          </PaginationContent>
        </Pagination>
      )}
    </div>
  );
}
